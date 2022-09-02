<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\ConfigurableAbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class StaticValueResolver extends ConfigurableAbstractValueResolver
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'STATIC';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false)
    {
        $value = parent::getValue($context, $settingValues, $optional);

        if (is_array($value)) {
            $items = [];
            foreach ($value as $item) {
                $items[] = $this->formatValue(sprintf('\'%s\'', $item));
            }

            return $items;
        }

        return $value ? $this->formatValue(sprintf('\'%s\'', $value)) : null;
    }
}
