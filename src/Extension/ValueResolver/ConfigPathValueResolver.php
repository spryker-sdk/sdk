<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class ConfigPathValueResolver extends OriginValueResolver
{
    /**
     * @var string
     */
    public const RESOLVER_ID = 'CONFIG_PATH';

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return static::RESOLVER_ID;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return ValueTypeEnum::TYPE_STRING;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $value = (string)parent::getValue($context, $settingValues, $optional);

        $projectLevelSettings = sprintf('%s/%s', $settingValues[Setting::PATH_PROJECT_DIR] ?? '', $value);
        $sdkLevelSettings = sprintf('%s/%s', $settingValues[Setting::PATH_SDK_DIR] ?? '', $value);

        return file_exists($projectLevelSettings) ? $projectLevelSettings : $sdkLevelSettings;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return $this->getRequiredSettingPaths();
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [Setting::PATH_PROJECT_DIR, Setting::PATH_SDK_DIR];
    }
}
