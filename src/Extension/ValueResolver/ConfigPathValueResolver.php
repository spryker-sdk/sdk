<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;

class ConfigPathValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
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
     * @var string
     */
    protected string $alias = '';

    /**
     * @var string
     */
    protected string $defaultValue = '';

    /**
     * @var string
     */
    protected string $description = 'Path to the config file';

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'CONFIG_PATH';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->defaultValue = (string)($values['defaultValue'] ?? '');
        $this->alias = (string)($values['name'] ?? '');

        if (isset($values['description'])) {
            $this->description = (string)$values['description'];
        }
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

        $projectLevelSettings = sprintf('%s/%s', $settingValues[static::PROJECT_DIR_SETTING] ?? '', $value);
        $sdkLevelSettings = sprintf('%s/%s', $settingValues[static::SDK_DIR_SETTING] ?? '', $value);

        return file_exists($projectLevelSettings) ? $projectLevelSettings : $sdkLevelSettings;
    }

    /**
     * {@inheritDoc}
     *
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
        return [static::PROJECT_DIR_SETTING, static::SDK_DIR_SETTING];
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
