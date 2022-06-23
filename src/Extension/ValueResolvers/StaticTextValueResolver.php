<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class StaticTextValueResolver extends StaticValueResolver
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'STATIC_TEXT';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): mixed
    {
        $value = parent::getValue($context, $settingValues, $optional);

        if (is_array($value)) {
            $items = [];
            foreach ($value as $item) {
                $items[] = sprintf('\'%s\'', $item);
            }

            return $items;
        }

        return $value ? sprintf('\'%s\'', $value) : null;
    }
}
