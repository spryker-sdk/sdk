<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class BusinessModelValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const ID = 'B2BC_TYPE';

    /**
     * @var string
     */
    public const ALIAS = 'business_model_url';

    /**
     * @var array
     */
    protected const REPOSITORIES = [
        'b2b' => 'https://github.com/spryker-shop/b2b-demo-shop.git',
        'b2c' => 'https://github.com/spryker-shop/b2c-demo-shop.git',
    ];

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return static::ID;
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
        $value = parent::getValue($context, $settingValues, $optional);

        return static::REPOSITORIES[$value];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Choose project for installation';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return ValueTypeEnum::TYPE_STRING;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return static::ALIAS;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return array_key_first(static::REPOSITORIES);
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
        return array_keys(static::REPOSITORIES);
    }
}
