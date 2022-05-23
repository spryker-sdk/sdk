<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow as WorkflowEntity;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
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
     * @var \Symfony\Component\Workflow\Workflow|null
     */
    protected ?Workflow $currentWorkflow = null;

    /**
     * @var \Symfony\Component\Workflow\Transition|null
     */
    protected ?Transition $currentTransition = null;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    protected ?WorkflowInterface $currentProjectWorkflow = null;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Workflow\Registry $workflows
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        Registry $workflows,
        WorkflowRepositoryInterface $workflowRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->workflows = $workflows;
        $this->workflowRepository = $workflowRepository;
    }

    /**
     * @return array<string, string>
     */
    public function getWorkflowMetadata(): array
    {
        $this->initializeWorkflow();
        if (!$this->currentWorkflow) {
            return [];
        }

        return $this->currentWorkflow->getMetadataStore()->getWorkflowMetadata();
    }

    /**
     * @return array<string>
     */
    public function getWorkflowTasks(): array
    {
        $this->initializeWorkflow();
        if (!$this->currentProjectWorkflow || !$this->currentWorkflow) {
            return [];
        }
        $enabledTransitions = $this->currentWorkflow->getEnabledTransitions($this->currentProjectWorkflow);
        $enabledTasksIds = [];
        $metaWorkflow = $this->currentWorkflow->getMetadataStore();
        foreach ($enabledTransitions as $enabledTransition) {
            $transactionTaskId = $metaWorkflow->getTransitionMetadata($enabledTransition)['task'] ?? null;
            $enabledTasksIds[$enabledTransition->getName()] = $transactionTaskId;
        }

        return $enabledTasksIds;
    }

    /**
     * @return void
     */
    protected function initializeWorkflow(): void
    {
        if ($this->currentProjectWorkflow) {
            return;
        }

        $projectIdSetting = $this->projectSettingRepository->getOneByPath(static::PROJECT_KEY);
        $this->currentProjectWorkflow = $this->workflowRepository->findOne($projectIdSetting->getValues());
        if (!$this->currentProjectWorkflow) {
            return;
        }
        $this->currentWorkflow = $this->workflows->get($this->currentProjectWorkflow, $this->currentProjectWorkflow->getWorkflow());
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return bool
     */
    public function initWorkflow(ContextInterface $context): bool
    {
        $this->initializeWorkflow();
        if (!$this->currentProjectWorkflow || !$this->currentWorkflow) {
            return true;
        }

        $taskId = $context->getTask()->getId();
        $enabledTransitions = $this->currentWorkflow->getEnabledTransitions($this->currentProjectWorkflow);
        $enabledTasksIds = [];
        $metaWorkflow = $this->currentWorkflow->getMetadataStore();
        foreach ($enabledTransitions as $enabledTransition) {
            $transactionTaskId = $metaWorkflow->getTransitionMetadata($enabledTransition)['task'] ?? null;
            if (!$transactionTaskId || $taskId === $transactionTaskId) {
                $this->currentTransition = $enabledTransition;

                return true;
            }
            $enabledTasksIds[] = $transactionTaskId;
        }

        $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);
        $context->addMessage(
            $taskId,
            new Message(
                sprintf(
                    'Running task is not executable for project workflow. Available tasks: %s',
                    implode(',', $enabledTasksIds),
                ),
                MessageInterface::ERROR,
            ),
        );

        return false;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function applyTransaction(ContextInterface $context): ContextInterface
    {
        if ($this->currentWorkflow && $context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            $context->addMessage(
                $context->getTask()->getId(),
                new Message(
                    'You cannot move to the next place in the workflow because your command failed',
                    MessageInterface::ERROR,
                ),
            );
            if ($this->currentTransition) {
                $errorMessage = $this->currentWorkflow->getMetadataStore()->getTransitionMetadata($this->currentTransition)['error'] ?? null;

                if ($errorMessage) {
                    $context->addMessage(
                        $context->getTask()->getId(),
                        new Message(
                            $errorMessage,
                            MessageInterface::INFO,
                        ),
                    );
                }
            }

            return $context;
        }

        if ($this->currentWorkflow && $this->currentTransition && $this->currentProjectWorkflow) {
            $this->currentWorkflow->apply(
                $this->currentProjectWorkflow,
                $this->currentTransition->getName(),
                ['context' => $context],
            );

            $this->workflowRepository->flush();
        }

        return $context;
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
}
