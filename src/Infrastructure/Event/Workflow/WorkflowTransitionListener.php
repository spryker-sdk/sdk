<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use DateTime;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Entity\WorkflowTransition;
use SprykerSdk\Sdk\Infrastructure\Entity\WorkflowTransition as WorkflowTransitionEntity;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;
use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\TransitionBlocker;
use Symfony\Component\Workflow\TransitionBlockerList;

class WorkflowTransitionListener
{
    /**
     * @var string
     */
    public const DATA_NEXT_TRANSACTION = 'nextTransition';

    /**
     * @var string
     */
    public const DATA_STATUS = 'status';

    /**
     * @var string
     */
    public const META_ALLOW_TO_FAIL = 'allowToFail';

    /**
     * @var string
     */
    public const META_KEY_ERROR = 'error';

    /**
     * @var string
     */
    public const META_KEY_TASK = 'task';

    /**
     * @var string
     */
    public const META_KEY_TRANSITION_RESOLVER = 'transition_resolver';

    /**
     * @var string
     */
    public const META_KEY_WORKFLOW_BEFORE = 'workflowBefore';

    /**
     * @var string
     */
    public const META_KEY_WORKFLOW_AFTER = 'workflowAfter';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner
     */
    protected WorkflowRunner $workflowRunner;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected WorkflowRepositoryInterface $workflowRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface
     */
    protected WorkflowTransitionRepositoryInterface $workflowTransitionRepository;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner $workflowRunner
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface $workflowTransitionRepository
     */
    public function __construct(
        ContainerInterface $container,
        TaskExecutor $taskExecutor,
        WorkflowRunner $workflowRunner,
        ProjectWorkflow $projectWorkflow,
        WorkflowRepositoryInterface $workflowRepository,
        WorkflowTransitionRepositoryInterface $workflowTransitionRepository
    ) {
        $this->container = $container;
        $this->taskExecutor = $taskExecutor;
        $this->workflowRunner = $workflowRunner;
        $this->projectWorkflow = $projectWorkflow;
        $this->workflowRepository = $workflowRepository;
        $this->workflowTransitionRepository = $workflowTransitionRepository;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @return void
     */
    public function execute(TransitionEvent $event): void
    {
        $transitionEntity = $this->startTransition($event);

        $this->tryRunWorkflow($event, $transitionEntity, static::META_KEY_WORKFLOW_BEFORE);
        $this->tryRunTask($event, $transitionEntity);
        $this->tryRunWorkflow($event, $transitionEntity, static::META_KEY_WORKFLOW_AFTER);

        $this->updateTransition($transitionEntity, WorkflowTransitionInterface::WORKFLOW_TRANSITION_FINISHED);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $transition
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return void
     */
    protected function tryRunTask(TransitionEvent $event, WorkflowTransitionInterface $transition): void
    {
        $task = $this->getTransitionMeta($event, static::META_KEY_TASK);
        if (!$task) {
            return;
        }

        $shouldRunTask = in_array($transition->getState(), [
            WorkflowTransitionInterface::WORKFLOW_TRANSITION_STARTED,
            WorkflowTransitionInterface::NESTED_WORKFLOW_STARTED,
            WorkflowTransitionInterface::NESTED_WORKFLOW_FINISHED,
            WorkflowTransitionInterface::WORKFLOW_TASK_FAILED,
        ]);
        if (!$shouldRunTask) {
            return;
        }

        $allowToFail = $this->getTransitionMeta($event, static::META_ALLOW_TO_FAIL);

        $context = $this->getContext($event);
        $context = $this->taskExecutor->execute($task, $context);
        $resolvedNextTransition = $this->resolverNextTransaction($event, $context);

        if (!$allowToFail && !$resolvedNextTransition && $context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            $this->updateTransition($transition, WorkflowTransitionInterface::WORKFLOW_TASK_FAILED, ['task' => $task]);
            $error = $this->getTransitionMeta($event, static::META_KEY_ERROR);
            if ($error) {
                throw $this->blockTransition($event, $error, MessageInterface::INFO);
            }

            throw $this->blockTransition(
                $event,
                'You cannot move to the next place in the workflow because your command failed',
                MessageInterface::ERROR,
            );
        }

        $transitionData = ['task' => $task, static::DATA_STATUS => $context->getExitCode(), static::DATA_NEXT_TRANSACTION => $resolvedNextTransition];

        if ($resolvedNextTransition) {
            $transitionData[static::DATA_NEXT_TRANSACTION] = $resolvedNextTransition;
        }

        $this->updateTransition(
            $transition,
            WorkflowTransitionInterface::WORKFLOW_TASK_SUCCEEDED,
            $transitionData,
        );
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return string|null
     */
    protected function resolverNextTransaction(TransitionEvent $event, ContextInterface $context): ?string
    {
        $transitionResolverService = $this->getTransitionMeta($event, static::META_KEY_TRANSITION_RESOLVER);
        if ($transitionResolverService && isset($transitionResolverService['service'])) {
            $transitionResolver = $this->container->get($transitionResolverService['service']);
            if ($transitionResolver instanceof TransitionResolverInterface) {
                return $transitionResolver->resolveTransition($context, $transitionResolverService['settings']);
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $transition
     * @param string $which
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return void
     */
    protected function tryRunWorkflow(
        TransitionEvent $event,
        WorkflowTransitionInterface $transition,
        string $which = self::META_KEY_WORKFLOW_BEFORE
    ): void {
        $transitionName = $event->getTransition() ? $event->getTransition()->getName() : '';
        $nestedWorkflowName = $this->getTransitionMeta($event, $which);

        if (!$transitionName || !$nestedWorkflowName) {
            return;
        }

        $context = $this->getContext($event);

        $nestedWorkflowCode = sprintf('%s.%s.%s', $transitionName, $which, $nestedWorkflowName);
        $nestedWorkflowEntity = $this->getOrCreateNestedWorkflow($event, $transition, $which);

        if ($this->projectWorkflow->isWorkflowFinished($nestedWorkflowEntity)) {
            return;
        }

        $this->workflowRunner->execute($nestedWorkflowCode, $context);

        $nestedWorkflowEntity = $this->getOrCreateNestedWorkflow($event, $transition, $which);
        if (!$this->projectWorkflow->isWorkflowFinished($nestedWorkflowEntity)) {
            throw $this->blockTransition(
                $event,
                sprintf('Nested workflow `%s` has not been finished', $nestedWorkflowCode),
                MessageInterface::ERROR,
            );
        }

        $this->updateTransition($transition, WorkflowTransitionInterface::NESTED_WORKFLOW_FINISHED);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface
     */
    protected function startTransition(TransitionEvent $event): WorkflowTransitionInterface
    {
        /** @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow */
        $workflow = $event->getSubject();
        $currentTransition = $event->getTransition() ? $event->getTransition()->getName() : '';

        $runningTransition = $this->projectWorkflow->getRunningTransition($workflow);

        if ($runningTransition && $runningTransition->getTransition() !== $currentTransition) {
            throw $this->blockTransition(
                $event,
                sprintf(
                    'Can\'t start transition `%s`, another transition `%s` is already running',
                    $currentTransition,
                    $runningTransition->getTransition(),
                ),
                MessageInterface::ERROR,
            );
        }

        if (!$runningTransition) {
            $runningTransition = $this->workflowTransitionRepository->save(
                new WorkflowTransitionEntity(
                    $workflow,
                    $workflow->getStatus(),
                    $currentTransition,
                    WorkflowTransitionInterface::WORKFLOW_TRANSITION_STARTED,
                ),
            );
        }

        return $runningTransition;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $transitionEntity
     * @param string $state
     * @param array $data
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface
     */
    protected function updateTransition(
        WorkflowTransitionInterface $transitionEntity,
        string $state,
        array $data = []
    ): WorkflowTransitionInterface {
        if (!$transitionEntity instanceof WorkflowTransition) {
            return $transitionEntity;
        }

        $transitionEntity->setState($state)
            ->setTime(new DateTime())
            ->mergeData($data);

        return $this->workflowTransitionRepository->save($transitionEntity);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param string $message
     * @param int $code
     *
     * @return \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     */
    protected function blockTransition(
        TransitionEvent $event,
        string $message,
        int $code
    ): NotEnabledTransitionException {
        return new NotEnabledTransitionException(
            $event->getSubject(),
            $event->getTransition() ? $event->getTransition()->getName() : 'Init workflow',
            $event->getWorkflow(),
            new TransitionBlockerList([
                new TransitionBlocker($message, (string)$code),
            ]),
            $event->getContext(),
        );
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param string $metaName
     *
     * @return mixed
     */
    protected function getTransitionMeta(Event $event, string $metaName): mixed
    {
        $transition = $event->getTransition();

        if (!$transition) {
            return null;
        }

        $transitionMeta = $event->getWorkflow()->getMetadataStore()->getTransitionMetadata($transition);

        return $transitionMeta[$metaName] ?? null;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function getContext(TransitionEvent $event): ContextInterface
    {
        /** @var \SprykerSdk\SdkContracts\Entity\ContextInterface $context */
        $context = $event->getContext()['context'] ?? null;

        if (!$context instanceof ContextInterface) {
            throw $this->blockTransition($event, 'Context must be provided for transition', MessageInterface::ERROR);
        }

        return $context;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $transition
     * @param string $which
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    protected function getOrCreateNestedWorkflow(
        Event $event,
        WorkflowTransitionInterface $transition,
        string $which
    ): WorkflowInterface {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Workflow $workflowEntity */
        $workflowEntity = $event->getSubject();
        $transitionName = $event->getTransition() ? $event->getTransition()->getName() : '';
        $nestedWorkflowName = $this->getTransitionMeta($event, $which) ?? '';

        $nestedWorkflowCode = sprintf('%s.%s.%s', $transitionName, $which, $nestedWorkflowName);

        $nestedWorkflowEntity = $this->workflowRepository->getWorkflow(
            $workflowEntity->getProject(),
            $nestedWorkflowCode,
        );

        if ($nestedWorkflowEntity) {
            return $nestedWorkflowEntity;
        }

        $nestedWorkflowEntity = new Workflow(
            $workflowEntity->getProject(),
            [],
            $nestedWorkflowName,
            $nestedWorkflowCode,
            $workflowEntity,
        );

        $this->workflowRepository->save($nestedWorkflowEntity);

        $this->updateTransition(
            $transition,
            WorkflowTransitionInterface::NESTED_WORKFLOW_STARTED,
            [sprintf('nested_%s_id', $which) => $nestedWorkflowEntity->getId()],
        );

        return $nestedWorkflowEntity;
    }
}
