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
        if (!$this->currentWorkflow || !$this->currentProjectWorkflow) {
            return [];
        }

        return $this->currentWorkflow->getMetadataStore()->getWorkflowMetadata();
    }

    /**
     * @param string|null $workflowName
     *
     * @return bool
     */
    public function initializeWorkflow(?string $workflowName = null): bool
    {
        if ($this->currentWorkflow && $this->currentProjectWorkflow) {
            return true;
        }

        $projectIdSetting = $this->projectSettingRepository->getOneByPath(static::PROJECT_KEY);
        $this->currentProjectWorkflow = $this->workflowRepository->getWorkflow($projectIdSetting->getValues(), $workflowName);
        if (!$this->currentProjectWorkflow) {
            return false;
        }
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
        $projectIdSetting = $this->projectSettingRepository->getOneByPath(static::PROJECT_KEY);

        return $this->workflowRepository->hasWorkflow($projectIdSetting->getValues());
    }

    /**
     * @return array<string>
     */
    public function getNextEnabledTransactions(): array
    {
        if (!$this->currentWorkflow || !$this->currentProjectWorkflow) {
            return [];
        }

        return array_map(function (Transition $transition) {
            return $transition->getName();
        }, $this->currentWorkflow->getEnabledTransitions($this->currentProjectWorkflow));
    }

    /**
     * @return array<string>
     */
    public function findInitializeWorkflows(): array
    {
        $projectIdSetting = $this->projectSettingRepository->getOneByPath(static::PROJECT_KEY);

        return array_map(function (WorkflowInterface $workflow) {
            return $workflow->getWorkflow();
        }, $this->workflowRepository->findWorkflows($projectIdSetting->getValues()));
    }

    /**
     * @param string $transitionName
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function applyTransaction(string $transitionName, ContextInterface $context): ContextInterface
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
                            MessageInterface::ERROR,
                        ),
                    );
                }
            }

            $this->workflowRepository->flush();
        }

        return $context;
    }
}
