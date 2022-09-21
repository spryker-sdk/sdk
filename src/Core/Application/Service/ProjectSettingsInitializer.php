<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingsInitializerProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectSettingsInitializerProcessor;

class ProjectSettingsInitializer implements ProjectSettingsInitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\SettingManager
     */
    protected SettingManager $settingManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingsInitializerProcessorInterface
     */
    protected ProjectSettingsInitializerProcessorInterface $projectSettingsInitializerProcessor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\SettingManager $settingManager
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectSettingsInitializerProcessor $projectSettingsInitializerProcessor
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        SettingManager $settingManager,
        ProjectSettingsInitializerProcessor $projectSettingsInitializerProcessor
    ) {
        $this->settingRepository = $settingRepository;
        $this->settingManager = $settingManager;
        $this->projectSettingsInitializerProcessor = $projectSettingsInitializerProcessor;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return void
     */
    public function initialize(ProjectSettingsInitDto $projectSettingsDto): void
    {
        $settings = $this->settingRepository->findProjectSettings();

        $settingsToSave = $this->projectSettingsInitializerProcessor->initialize($settings, $projectSettingsDto);

        $this->settingManager->writeSettings($settingsToSave);
    }

    /**
     * @return bool
     */
    public function isProjectSettingsInitialised(): bool
    {
        return $this->projectSettingsInitializerProcessor->isInitialized();
    }
}
