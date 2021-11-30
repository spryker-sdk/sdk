<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;

class SprykConfigurationValueResolver extends AbstractValueResolver
{
    public const NAMESPACE = 'NAMESPACE';

    /**
     * @return string
     */
    public function getId(): string
    {
        return static::NAMESPACE;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'namespace';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Namespace name';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return 'Pyz';
    }

    /**
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $resolvedValues = []): array
    {
        return [
            'Pyz',
            'SprykerShop',
            'SprykerEco',
            'Spryker',
            'SprykerSdk',
        ];
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
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
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
