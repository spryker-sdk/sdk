<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;

class NamespaceValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const ALIAS = 'namespace';

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'NAMESPACE';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias(): string
    {
        return static::ALIAS;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Namespace name';
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
     * @return mixed
     */
    public function getDefaultValue()
    {
        return 'Pyz';
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return ['projectNamespaces', 'coreNamespaces'];
    }

    /**
     * {@inheritDoc}
     *
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
    protected function getValueFromSettings(array $settingValues)
    {
        return null;
    }
}
