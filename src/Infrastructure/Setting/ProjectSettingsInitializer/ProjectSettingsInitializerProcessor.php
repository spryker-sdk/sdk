<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingsInitializerProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class ProjectSettingsInitializerProcessor implements ProjectSettingsInitializerProcessorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion
     */
    protected ChangeDefaultValueQuestion $changeDefaultValueQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion
     */
    protected SettingValueQuestion $settingValueQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry
     */
    protected SettingInitializerRegistry $settingInitializerRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectFilesInitializer
     */
    protected ProjectFilesInitializer $projectFilesInitializer;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion $changeDefaultValueQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion $settingValueQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry $settingInitializerRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectFilesInitializer $projectFilesInitializer
     */
    public function __construct(
        ChangeDefaultValueQuestion $changeDefaultValueQuestion,
        SettingValueQuestion $settingValueQuestion,
        SettingInitializerRegistry $settingInitializerRegistry,
        ProjectFilesInitializer $projectFilesInitializer
    ) {
        $this->changeDefaultValueQuestion = $changeDefaultValueQuestion;
        $this->settingValueQuestion = $settingValueQuestion;
        $this->settingInitializerRegistry = $settingInitializerRegistry;
        $this->projectFilesInitializer = $projectFilesInitializer;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function initialize(array $settings, ProjectSettingsInitDto $projectSettingsDto): array
    {
        $settingsToSave = $this->handleSettingsValues($settings, $projectSettingsDto);
        $this->initializeSettings($settings);
        $this->projectFilesInitializer->initProjectFiles();

        return $settingsToSave;
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->projectFilesInitializer->isProjectSettingsInitialised();
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function handleSettingsValues(array $settings, ProjectSettingsInitDto $projectSettingsDto): array
    {
        $settingsToSave = [];

        foreach ($settings as $setting) {
            $settingToSave = $this->handleProjectSetting($setting, $projectSettingsDto);

            if ($settingToSave === null) {
                continue;
            }

            $settingsToSave[] = $settingToSave;
        }

        return $settingsToSave;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|null
     */
    protected function handleProjectSetting(SettingInterface $setting, ProjectSettingsInitDto $projectSettingsDto): ?SettingInterface
    {
        $values = $projectSettingsDto->getSettingValue($setting->getPath());

        if ($projectSettingsDto->useDefaultValue() || $this->isInitializedByInitializer($setting)) {
            return null;
        }

        if ($this->isValuesEmpty($values)) {
            if (!$this->changeDefaultValueQuestion->ask($setting)) {
                return null;
            }

            $values = $this->settingValueQuestion->ask($setting, $setting->getValues());
        }

        if (!$this->isValuesChanged($setting, $values)) {
            return null;
        }

        $setting->setValues($values);

        return $setting;
    }

    /**
     * @param mixed $values
     *
     * @return bool
     */
    protected function isValuesEmpty($values): bool
    {
        return $values === null || $values === [];
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return void
     */
    protected function initializeSettings(array $settings): void
    {
        foreach ($settings as $setting) {
            $initializerName = $setting->getInitializer();

            if ($initializerName === null || !$this->settingInitializerRegistry->hasSettingInitializer($initializerName)) {
                continue;
            }

            $this->settingInitializerRegistry->getSettingInitializer($initializerName)->initialize($setting);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return bool
     */
    protected function isInitializedByInitializer(SettingInterface $setting): bool
    {
        return !$setting->hasInitialization()
            || ($setting->getInitializer() && $setting->getType() === ValueTypeEnum::TYPE_UUID);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     * @param mixed $values
     *
     * @return bool
     */
    protected function isValuesChanged(SettingInterface $setting, $values): bool
    {
        $values = [
            ValueTypeEnum::TYPE_BOOL => (bool)$values,
            ValueTypeEnum::TYPE_ARRAY => (array)$values,
        ][$setting->getType()] ?? (string)$values;

        return $setting->getType() === ValueTypeEnum::TYPE_ARRAY || $values !== $setting->getValues();
    }
}
