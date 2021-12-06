<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;

class NamespaceValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const ALIAS = 'namespace';

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'NAMESPACE';
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return static::ALIAS;
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
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return array_merge($settingValues['projectNamespaces'], $settingValues['coreNamespaces']);
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
        return ['projectNamespaces', 'coreNamespaces'];
    }
}
