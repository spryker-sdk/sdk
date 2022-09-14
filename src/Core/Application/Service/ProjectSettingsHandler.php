<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectSettingsInitializer;

class ProjectSettingsHandler
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
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectSettingsInitializer
     */
    protected ProjectSettingsInitializer $projectSettingsInitializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\SettingManager $settingManager
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectSettingsInitializer $projectSettingsInitializer
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        SettingManager $settingManager,
        ProjectSettingsInitializer $projectSettingsInitializer
    ) {
        $this->settingRepository = $settingRepository;
        $this->settingManager = $settingManager;
        $this->projectSettingsInitializer = $projectSettingsInitializer;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return void
     */
    public function handleInitialize(ProjectSettingsInitDto $projectSettingsDto): void
    {
        $settings = $this->settingRepository->findProjectSettings();

        $settingsToSave = $this->projectSettingsInitializer->initialize($settings, $projectSettingsDto);

        $this->writeProjectSettings($settingsToSave);
    }

    /**
     * @return bool
     */
    public function isProjectSettingsInitialised(): bool
    {
        return $this->projectSettingsInitializer->isInitialized();
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return void
     */
    protected function writeProjectSettings(array $settings): void
    {
        $projectValues = [];

        foreach ($settings as $setting) {
            $projectValues[$setting->getPath()] = $setting->getValues();
        }

        $this->settingManager->setSettings($projectValues);
    }
}
