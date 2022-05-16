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

    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): mixed
    {
        $value = parent::getValue($context, $settingValues, $optional);

        return $value ? sprintf('\'%s\'', $value): null;
    }
}
