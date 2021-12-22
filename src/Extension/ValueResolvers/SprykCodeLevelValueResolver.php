<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $resolvedValues = $context->getResolvedValues();

        if (!array_key_exists(NamespaceValueResolver::ALIAS, $resolvedValues)) {
            return $this->getDefaultValue();
        }

        if (in_array($resolvedValues[NamespaceValueResolver::ALIAS], (array)$settingValues['coreNamespaces'], false)) {
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
        return ['coreNamespaces'];
    }
}
