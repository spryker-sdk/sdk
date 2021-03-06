<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Settings\Initializers;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

class ProjectWorkflowInitializer implements SettingInitializerInterface, SettingChoicesProviderInterface
{
    /**
     * @var string
     */
    protected const PROJECT_KEY_SETTING = 'project_key';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected WorkflowRepositoryInterface $workflowRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface $workflowRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        WorkflowRepositoryInterface $workflowRepository,
        ProjectWorkflow $projectWorkflow
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->workflowRepository = $workflowRepository;
        $this->projectWorkflow = $projectWorkflow;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return void
     */
    public function initialize(SettingInterface $setting): void
    {
        if (!$setting->getValues()) {
            return;
        }
        $workflow = (string)$setting->getValues();

        $projectKeySetting = $this->projectSettingRepository->findOneByPath(static::PROJECT_KEY_SETTING);
        if ($projectKeySetting && $projectKeySetting->getValues()) {
            $projectKey = (string)$projectKeySetting->getValues();
            $this->workflowRepository->save(new Workflow($projectKey, [], $workflow));
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return array<string>
     */
    public function getChoices(SettingInterface $setting): array
    {
        return $this->projectWorkflow->getAll();
    }
}
