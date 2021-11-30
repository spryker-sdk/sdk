<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;

class SprykCodeLevelValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    protected const CORE = 'core';

    /**
     * @var string
     */
    protected const PROJECT = 'project';

    /**
     * @var array
     */
    protected const CORE_NAMESPACE = [
        'SprykerShop',
        'SprykerEco',
        'Spryker',
        'SprykerSdk',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'CORE';
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'mode';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Core level';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional = false, array $resolvedValues = []): mixed
    {
        if (!array_key_exists(SprykConfigurationValueResolver::NAMESPACE, $resolvedValues)) {
            return $this->getDefaultValue();
        }

        if (in_array($resolvedValues[SprykConfigurationValueResolver::NAMESPACE], static::CORE_NAMESPACE)) {
            return static::CORE;
        }

        return $this->getDefaultValue();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return static::PROJECT;
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }
}
