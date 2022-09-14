<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingInitializerRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class ProjectSettingsInitializer implements ProjectSettingsInitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion
     */
    protected ChangeDefaultValueQuestion $changeDefaultValueQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion
     */
    protected SettingValueQuestion $settingValueQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingInitializerRegistry
     */
    protected SettingInitializerRegistry $settingInitializerRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectFilesInitializer
     */
    protected ProjectFilesInitializer $projectFilesInitializer;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion $changeDefaultValueQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion $settingValueQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingInitializerRegistry $settingInitializerRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectFilesInitializer $projectFilesInitializer
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
        $inputSettingValue = $projectSettingsDto->getSettingValue($setting->getPath());

        if ($this->isSettingNotApplicable($setting, $inputSettingValue)) {
            return null;
        }

        $values = $inputSettingValue ?? $setting->getValues();

        $useDefaultValue = $this->isValueEmpty($inputSettingValue)
            ? !$this->changeDefaultValueQuestion->ask($setting)
            : $projectSettingsDto->useDefaultValue();

        if ($useDefaultValue && $this->isValueEmpty($inputSettingValue)) {
            return null;
        }

        if ($this->isValueEmpty($inputSettingValue)) {
            $values = $this->settingValueQuestion->ask($setting, $values);
        }

        if (!$this->isValuesChanged($setting, $values)) {
            return null;
        }

        $setting->setValues($values);

        return $setting;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return void
     */
    protected function initializeSettings(array $settings): void
    {
        foreach ($settings as $setting) {
            $initializerName = $setting->getInitializer() ?? '';

            if (!$this->settingInitializerRegistry->hasSettingInitializer($initializerName)) {
                continue;
            }

            $this->settingInitializerRegistry->getSettingInitializer($initializerName)->initialize($setting);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     * @param mixed $inputSettingValue
     *
     * @return bool
     */
    protected function isSettingNotApplicable(SettingInterface $setting, $inputSettingValue): bool
    {
        return ($this->isValueEmpty($inputSettingValue) && !$setting->hasInitialization())
            || ($setting->getInitializer() && $setting->getType() === ValueTypeEnum::TYPE_UUID);
    }

    /**
     * @param mixed|null $values
     *
     * @return bool
     */
    protected function isValueEmpty($values): bool
    {
        return $values === null || $values === '' || $values === [];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     * @param mixed $values
     *
     * @return bool
     */
    protected function isValuesChanged(SettingInterface $setting, $values): bool
    {
        $values = [ValueTypeEnum::TYPE_BOOLEAN => (bool)$values, ValueTypeEnum::TYPE_ARRAY => (array)$values][$setting->getType()] ?? (string)$values;

        return $setting->getType() === ValueTypeEnum::TYPE_ARRAY || $values !== $setting->getValues();
    }
}
