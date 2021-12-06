<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Extension\ValueResolvers;

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
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @return string
     */
    public function getValue(array $settingValues, bool $optional = false, array $resolvedValues = []): string
    {
        $defaultValue = parent::getValue($settingValues, $optional);

        return !$defaultValue ? '' : sprintf('--%s', $this->flag);
    }
}
