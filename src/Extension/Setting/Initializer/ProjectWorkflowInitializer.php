<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Setting\Initializer;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow as WorkflowEntity;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow as WorkflowComponent;

class ProjectWorkflowInitializer implements SettingInitializerInterface, SettingChoicesProviderInterface
{
    /**
     * @var string
     */
    protected const PROJECT_KEY_SETTING = 'project_key';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected WorkflowRepositoryInterface $workflowRepository;

    /**
     * @var \Symfony\Component\Workflow\Registry
     */
    protected Registry $workflows;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     * @param \Symfony\Component\Workflow\Registry $workflows
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        WorkflowRepositoryInterface $workflowRepository,
        Registry $workflows
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->workflowRepository = $workflowRepository;
        $this->workflows = $workflows;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return void
     */
    public function initialize(SettingInterface $setting): void
    {
        if (!$setting->getValues()) {
            return;
        }
        $workflows = $setting->getValues();

        $projectKeySetting = $this->projectSettingRepository->findOneByPath(static::PROJECT_KEY_SETTING);
        if ($projectKeySetting && $projectKeySetting->getValues()) {
            $projectKey = (string)$projectKeySetting->getValues();
            $existingWorkflows = $this->workflowRepository->getWorkflows($projectKey);
            $existingWorkflowNames = [];
            foreach ($existingWorkflows as $existingWorkflow) {
                $existingWorkflowNames[] = $existingWorkflow->getWorkflow();
            }
            foreach ($workflows as $workflow) {
                if (in_array($workflow, $existingWorkflowNames)) {
                    continue;
                }
                $this->workflowRepository->save(new Workflow($projectKey, [], $workflow));
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return array<string>
     */
    public function getChoices(SettingInterface $setting): array
    {
        return array_map(
            fn (WorkflowComponent $workflow): string => $workflow->getName(),
            $this->workflows->all(new WorkflowEntity('', [], '')),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'project_workflow_initializer';
    }
}
