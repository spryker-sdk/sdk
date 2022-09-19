<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

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
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'CORE';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'mode';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Core level';
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
        $resolvedValues = $context->getResolvedValues();
        $namespaceAlias = '%' . NamespaceValueResolver::ALIAS . '%';
        if (!array_key_exists($namespaceAlias, $resolvedValues)) {
            return $this->getDefaultValue();
        }

        if (in_array($resolvedValues[$namespaceAlias], (array)$settingValues['coreNamespaces'], false)) {
            return static::CORE;
        }

        return $this->getDefaultValue();
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return static::PROJECT;
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
     * {@inheritDoc}
     *
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return ['coreNamespaces'];
    }
}
