<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Domain\Entity\Context;

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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(Context $context, array $settingValues, bool $optional = false): string
    {
        $defaultValue = parent::getValue($context, $settingValues, $optional);

        return !$defaultValue ? '' : sprintf('--%s', $this->flag);
    }
}
