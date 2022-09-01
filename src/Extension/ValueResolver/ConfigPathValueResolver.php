<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\ConfigurableAbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ConfigPathValueResolver extends ConfigurableAbstractValueResolver
{
    /**
     * @var string
     */
    protected const PROJECT_DIR_SETTING = 'project_dir';

    /**
     * @var string
     */
    protected const SDK_DIR_SETTING = 'sdk_dir';

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'CONFIG_PATH';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $value = (string)parent::getValue($context, $settingValues, $optional);

        $projectLevelSettings = sprintf('%s/%s', $settingValues[static::PROJECT_DIR_SETTING] ?? '', $value);
        $sdkLevelSettings = sprintf('%s/%s', $settingValues[static::SDK_DIR_SETTING] ?? '', $value);

        return file_exists($projectLevelSettings) ? $projectLevelSettings : $sdkLevelSettings;
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [static::PROJECT_DIR_SETTING, static::SDK_DIR_SETTING];
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return $this->getRequiredSettingPaths();
    }
}
