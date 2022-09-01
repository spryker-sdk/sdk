<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\ConfigurableAbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class PriorityPathValueResolver extends ConfigurableAbstractValueResolver
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
        return 'PRIORITY_PATH';
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

        $projectDir = $settingValues[static::PROJECT_DIR_SETTING];
        $sdkDir = $settingValues[static::SDK_DIR_SETTING];

        if (strpos($projectDir, DIRECTORY_SEPARATOR, -1) === 0) {
            $projectDir = rtrim($projectDir, DIRECTORY_SEPARATOR);
        }
        if (strpos($sdkDir, DIRECTORY_SEPARATOR, -1) === 0) {
            $sdkDir = rtrim($sdkDir, DIRECTORY_SEPARATOR);
        }

        $projectLevelSettings = sprintf('%s/%s', $projectDir ?? '', $value);
        $sdkLevelSettings = sprintf('%s/%s', $sdkDir ?? '', $value);

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

    /**
     * @param array $settingValues
     *
     * @return string|null
     */
    protected function getValueFromSettings(array $settingValues): ?string
    {
        return null;
    }
}
