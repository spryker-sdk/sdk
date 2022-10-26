<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\Setting;

class SprykCodeLevelValueResolver extends OriginValueResolver
{
    /**
     * @var string
     */
    protected const CORE = 'core';

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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $resolvedValues = $context->getResolvedValues();
        $namespaceAlias = '%' . $this->getAlias() . '%';
        if (!array_key_exists($namespaceAlias, $resolvedValues)) {
            return $this->formatValue($this->getDefaultValue());
        }

        if (in_array($resolvedValues[$namespaceAlias], (array)$settingValues[Setting::PATH_CORE_NAMESPACES], false)) {
            return $this->formatValue(static::CORE);
        }

        return $this->formatValue($this->getDefaultValue());
    }
}
