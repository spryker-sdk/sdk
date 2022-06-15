<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowEventRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Entity\WorkflowEvent as WorkflowEventEntity;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowEventInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
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
    public const META_KEY_ERROR = 'error';

    /**
     * @var string
     */
    public const META_KEY_TASK = 'task';

    /**
     * @var string
     */
    public const META_KEY_WORKFLOW_BEFORE = 'workflowBefore';

    /**
     * @var string
     */
    public const META_KEY_WORKFLOW_AFTER = 'workflowAfter';

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
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowEventRepositoryInterface
     */
    protected WorkflowEventRepositoryInterface $workflowEventRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner $workflowRunner
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowEventRepositoryInterface $workflowEventRepository
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        WorkflowRunner $workflowRunner,
        ProjectWorkflow $projectWorkflow,
        WorkflowRepositoryInterface $workflowRepository,
        WorkflowEventRepositoryInterface $workflowEventRepository
    ) {
        $this->taskExecutor = $taskExecutor;
        $this->workflowRunner = $workflowRunner;
        $this->projectWorkflow = $projectWorkflow;
        $this->workflowRepository = $workflowRepository;
        $this->workflowEventRepository = $workflowEventRepository;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @return void
     */
    public function execute(TransitionEvent $event): void
    {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Workflow $workflowEntity */
        $workflowEntity = $event->getSubject();
        $startedTransition = $this->projectWorkflow->getStartedTransition($workflowEntity);
        if (!$startedTransition) {
            $this->addWorkflowEvent($event, WorkflowEventInterface::EVENT_WORKFLOW_TRANSITION_STARTED);
        }

        $this->tryRunWorkflow($event, static::META_KEY_WORKFLOW_BEFORE);
        $this->tryRunTask($event);
        $this->tryRunWorkflow($event, static::META_KEY_WORKFLOW_AFTER);

        $this->addWorkflowEvent($event, WorkflowEventInterface::EVENT_WORKFLOW_TRANSITION_FINISHED);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return void
     */
    protected function tryRunTask(TransitionEvent $event): void
    {
        $task = $this->getTransitionMeta($event, static::META_KEY_TASK);
        if (!$task) {
            return;
        }

        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Workflow $workflowEntity */
        $workflowEntity = $event->getSubject();
        $taskExecutedEvent = $this->workflowEventRepository->searchByWorkflow(
            $workflowEntity,
            $event->getTransition() ? $event->getTransition()->getName() : null,
            [WorkflowEventInterface::EVENT_WORKFLOW_TASK_SUCCEEDED],
        );

        if ($taskExecutedEvent) {
            return;
        }

        $context = $this->getContext($event);
        $context = $this->taskExecutor->execute($task, $context);

        if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            $this->addWorkflowEvent($event, WorkflowEventInterface::EVENT_WORKFLOW_TASK_FAILED, ['task' => $task]);
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

        $this->addWorkflowEvent($event, WorkflowEventInterface::EVENT_WORKFLOW_TASK_SUCCEEDED, ['task' => $task]);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param string $which
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return void
     */
    protected function tryRunWorkflow(TransitionEvent $event, string $which = self::META_KEY_WORKFLOW_BEFORE): void
    {
        $transitionName = $event->getTransition() ? $event->getTransition()->getName() : '';
        $nestedWorkflowName = $this->getTransitionMeta($event, $which);

        if (!$transitionName || !$nestedWorkflowName) {
            return;
        }

        $context = $this->getContext($event);

        $nestedWorkflowCode = sprintf('%s.%s.%s', $transitionName, $which, $nestedWorkflowName);
        $nestedWorkflowEntity = $this->getOrCreateNestedWorkflow($event, $which);

        if ($this->projectWorkflow->isWorkflowFinished($nestedWorkflowEntity)) {
            return;
        }

        $this->workflowRunner->execute($nestedWorkflowCode, $context);

        if (!$this->projectWorkflow->isWorkflowFinished($this->getOrCreateNestedWorkflow($event, $which))) {
            throw $this->blockTransition(
                $event,
                sprintf('Nested workflow `%s` has not been finished', $nestedWorkflowCode),
                MessageInterface::ERROR,
            );
        }

        $this->addWorkflowEvent(
            $event,
            WorkflowEventInterface::EVENT_NESTED_WORKFLOW_FINISHED,
            ['code' => $nestedWorkflowCode],
        );
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
     * @return string|null
     */
    protected function getTransitionMeta(Event $event, string $metaName): ?string
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
     * @param string $which
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    protected function getOrCreateNestedWorkflow(Event $event, string $which): WorkflowInterface
    {
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

        $this->addWorkflowEvent(
            $event,
            WorkflowEventInterface::EVENT_NESTED_WORKFLOW_STARTED,
            ['code' => $nestedWorkflowCode],
        );

        return $nestedWorkflowEntity;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param string $eventName
     * @param array $data
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface
     */
    protected function addWorkflowEvent(Event $event, string $eventName, array $data = []): WorkflowEventInterface
    {
        /** @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow */
        $workflow = $event->getSubject();

        return $this->workflowEventRepository->save(
            new WorkflowEventEntity(
                $event->getMarking()->getPlaces(),
                $event->getTransition() ? $event->getTransition()->getName() : '',
                $eventName,
                $data,
                $workflow,
            ),
        );
    }
}
