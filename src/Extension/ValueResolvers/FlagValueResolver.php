<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class FlagValueResolver extends StaticValueResolver
{
    /**
     * @var string
     */
    protected string $flag;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'FLAG';
    }

    /**
     * @param array $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        parent::configure($values);

        $this->flag = $values['flag'] ?? $this->alias;
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
        $defaultValue = parent::getValue($context, $settingValues, $optional);

        return !$defaultValue ? '' : sprintf('--%s', $this->flag);
    }
}
