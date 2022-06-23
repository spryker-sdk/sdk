<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\ProjectWorkflowException;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow as WorkflowEntity;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class ProjectWorkflow
{
    /**
     * @var string
     */
    public const PROJECT_KEY = 'project_key';

    /**
     * @var string
     */
    public const WORKFLOW = 'workflow';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \Symfony\Component\Workflow\Registry
     */
    protected Registry $workflows;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected WorkflowRepositoryInterface $workflowRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface
     */
    protected WorkflowTransitionRepositoryInterface $workflowTransitionRepository;

    /**
     * @var \Symfony\Component\Workflow\Workflow|null
     */
    protected ?Workflow $currentWorkflow = null;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    protected ?WorkflowInterface $currentProjectWorkflow = null;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Workflow\Registry $workflows
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface $workflowTransitionRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        Registry $workflows,
        WorkflowRepositoryInterface $workflowRepository,
        WorkflowTransitionRepositoryInterface $workflowTransitionRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->workflows = $workflows;
        $this->workflowRepository = $workflowRepository;
        $this->workflowTransitionRepository = $workflowTransitionRepository;
    }

    /**
     * @return string
     */
    protected function getProjectId(): string
    {
        return (string)$this->projectSettingRepository->getOneByPath(static::PROJECT_KEY)->getValues();
    }

    /**
     * @return array
     */
    public function getProjectWorkflows(): array
    {
        return (array)$this->projectSettingRepository->getOneByPath(static::WORKFLOW)->getValues();
    }

    /**
     * @return array<string, string>
     */
    public function getWorkflowMetadata(): array
    {
        if (!$this->currentWorkflow || !$this->currentProjectWorkflow) {
            return [];
        }

        return $this->currentWorkflow->getMetadataStore()->getWorkflowMetadata();
    }

    /**
     * @param string|null $workflowName
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\ProjectWorkflowException
     *
     * @return bool
     */
    public function initializeWorkflow(?string $workflowName = null): bool
    {
        if (!$this->getProjectId()) {
            throw new ProjectWorkflowException('Project is not initialized. Run the `sdk:init:project` command.');
        }

        if (
            $this->currentWorkflow
            && $this->currentProjectWorkflow
            && ($workflowName === null || $this->currentWorkflow->getName() === $workflowName)
        ) {
            return true;
        }

        $this->currentProjectWorkflow = $this->workflowRepository->getWorkflow($this->getProjectId(), $workflowName);

        if (!$this->currentProjectWorkflow) {
            if (!$workflowName || $this->getProjectWorkflows() || !in_array($workflowName, $this->getAll())) {
                return false;
            }

            $this->currentProjectWorkflow = $this->workflowRepository->save(
                new WorkflowEntity($this->getProjectId(), [], $workflowName),
            );
        }

        $this->currentWorkflow = $this->workflows->get($this->currentProjectWorkflow, $this->currentProjectWorkflow->getWorkflow());

        return true;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\ProjectWorkflowException
     *
     * @return bool
     */
    public function restartWorkflow(): bool
    {
        if (!$this->currentWorkflow || !$this->currentProjectWorkflow) {
            throw new ProjectWorkflowException('Workflow is not initialized');
        }

        if ($this->currentProjectWorkflow->getParent()) {
            throw new ProjectWorkflowException('Restarting children workflows is not permitted');
        }

        $removedWorkflow = $this->workflowRepository->remove($this->currentProjectWorkflow);
        $this->currentProjectWorkflow = $this->workflowRepository->save(
            new WorkflowEntity($removedWorkflow->getProject(), [], $removedWorkflow->getWorkflow()),
        );

        $this->currentWorkflow = $this->workflows->get($this->currentProjectWorkflow, $this->currentProjectWorkflow->getWorkflow());

        return true;
    }

    /**
     * @return array<string>
     */
    public function getAll(): array
    {
        return array_map(
            fn (Workflow $workflow): string => $workflow->getName(),
            $this->workflows->all(new WorkflowEntity('', [], '')),
        );
    }

    /**
     * @return bool
     */
    public function hasWorkflow(): bool
    {
        return $this->workflowRepository->hasWorkflow($this->getProjectId());
    }

    /**
     * @return array<string>
     */
    public function getNextEnabledTransitions(): array
    {
        if (!$this->currentWorkflow || !$this->currentProjectWorkflow) {
            return [];
        }

        return array_map(
            fn (Transition $transition): string => $transition->getName(),
            $this->currentWorkflow->getEnabledTransitions($this->currentProjectWorkflow),
        );
    }

    /**
     * @return array<string>
     */
    public function findInitializedWorkflows(): array
    {
        return array_map(
            fn (WorkflowInterface $workflow): string => $workflow->getWorkflow(),
            $this->workflowRepository->findWorkflows($this->getProjectId()),
        );
    }

    /**
     * @param string $transitionName
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function applyTransition(string $transitionName, ContextInterface $context): ContextInterface
    {
        if ($this->currentWorkflow && $this->currentProjectWorkflow) {
            try {
                $this->currentWorkflow->apply(
                    $this->currentProjectWorkflow,
                    $transitionName,
                    ['context' => &$context],
                );
            } catch (NotEnabledTransitionException $exception) {
                /** @var \Symfony\Component\Workflow\TransitionBlocker $transitionBlocker */
                foreach ($exception->getTransitionBlockerList() as $transitionBlocker) {
                    $context->addMessage(
                        $transitionName,
                        new Message(
                            $transitionBlocker->getMessage(),
                            (int)$transitionBlocker->getCode() ?: MessageInterface::ERROR,
                        ),
                    );
                }
            }

            $this->workflowRepository->flush();
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null $workflow
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\ProjectWorkflowException
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface|null
     */
    public function getRunningTransition(?WorkflowInterface $workflow = null): ?WorkflowTransitionInterface
    {
        $workflow = $workflow ?? $this->currentProjectWorkflow;

        if (!$workflow) {
            throw new ProjectWorkflowException('Workflow is not initialized');
        }

        $lastTransition = $this->workflowTransitionRepository->findLast($workflow);

        if (!$lastTransition) {
            return null;
        }

        if ($lastTransition->getState() !== WorkflowTransitionInterface::WORKFLOW_TRANSITION_FINISHED) {
            return $lastTransition;
        }

        return null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null $workflow
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\ProjectWorkflowException
     *
     * @return bool
     */
    public function isWorkflowFinished(?WorkflowInterface $workflow = null): bool
    {
        $workflow = $workflow ?? $this->currentProjectWorkflow;

        if (!$workflow) {
            throw new ProjectWorkflowException('Workflow is not initialized');
        }

        $workflowEngine = $this->workflows->get($workflow, $workflow->getWorkflow());

        return count($workflowEngine->getEnabledTransitions($workflow)) === 0;
    }
}
