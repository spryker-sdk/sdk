<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
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
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return null;
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
     * @return mixed
     */
    public function getDefaultValue()
    {
        return static::PROJECT;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return ['coreNamespaces'];
    }
}
