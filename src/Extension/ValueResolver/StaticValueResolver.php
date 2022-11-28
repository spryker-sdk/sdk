<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class StaticValueResolver extends OriginValueResolver
{
    /**
     * @var string
     */
    public const RESOLVER_ID = 'STATIC';

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return static::RESOLVER_ID;
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
        $value = parent::getValue($context, $settingValues, $optional);

        if ($value === null) {
            return null;
        }

        if (!is_array($value)) {
            return $value ? $this->formatValue(sprintf('\'%s\'', $value)) : null;
        }

        $items = [];
        foreach ($value as $item) {
            $items[] = $this->formatValue(sprintf('\'%s\'', $item));
        }

        return implode(' ', $items);
    }
}
