<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class NamespaceValueResolver extends OriginValueResolver
{
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false)
    {
        return $this->formatValue(parent::getValue($context, $settingValues, $optional));
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
}
