<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use ReflectionClass;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
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
    public const PROJECT_ID_KEY = 'project_id';

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
    protected ?Transition $currentTransaction = null;

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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return bool
     */
    public function initWorkflow(ContextInterface $context): bool
    {
        $projectIdSetting = $this->projectSettingRepository->getOneByPath(static::PROJECT_ID_KEY);

        $this->currentProjectWorkflow = $this->workflowRepository->findOne($projectIdSetting->getValues());
        if (!$this->currentProjectWorkflow) {
            return true;
        }

        $taskId = $context->getTask()->getId();
        $this->currentWorkflow = $this->workflows->get($this->currentProjectWorkflow, $this->currentProjectWorkflow->getWorkflow());
        $enabledTransactions = $this->currentWorkflow->getEnabledTransitions($this->currentProjectWorkflow);
        $enabledTasksIds = [];
        foreach ($enabledTransactions as $enabledTransaction) {
            $transactionTaskId = $this->currentWorkflow->getMetadataStore()->getTransitionMetadata($enabledTransaction)['task'] ?? null;
            if (!$transactionTaskId || $taskId === $transactionTaskId) {
                $this->currentTransaction = $enabledTransaction;

                return true;
            }
            $enabledTasksIds[] = $transactionTaskId;
        }

        $context->setExitCode(0);
        $context->addMessage(
            $taskId,
            new Message(
                sprintf(
                    'Running task is not executable for project work flow. Available tasks: %s',
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
     * @return void
     */
    public function applyTransaction(ContextInterface $context): void
    {
        if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            $context->addMessage(
                $context->getTask()->getId(),
                new Message(
                    'You cannot move to the next place in the workflow because your command failed',
                    MessageInterface::ERROR,
                ),
            );

            return;
        }

        if ($this->currentWorkflow && $this->currentTransaction && $this->currentProjectWorkflow) {
            $this->currentWorkflow->apply(
                $this->currentProjectWorkflow,
                $this->currentTransaction->getName(),
                $this->convertContextToArray($context),
            );

            $this->workflowRepository->flush();
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return array
     */
    protected function convertContextToArray(ContextInterface $context): array
    {
        $reflectionClass = new ReflectionClass(get_class($context));
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($context);
            $property->setAccessible(false);
        }

        return $array;
    }
}
