<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ArrayOptionValueResolver extends StaticValueResolver
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = true)
    {
        $value = parent::getValue($context, $settingValues, $optional);

        if ($value === null) {
            return null;
        }

        $values = (array)preg_split("/\r\n|\n|\r/", $value);

        $options = array_map(
            function ($valueParam) {
                return sprintf('--%s=\'%s\'', $this->getAlias(), $valueParam);
            },
            $values,
        );

        return implode(' ', $options);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'ARRAY_OPTION';
    }
}
