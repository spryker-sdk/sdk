<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

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
     * @return string
     */
    public function getId(): string
    {
        return static::ID;
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
        $value = parent::getValue($context, $settingValues, $optional);

        return static::REPOSITORIES[$value];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Choose project for installation';
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return static::ALIAS;
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return array_key_first(static::REPOSITORIES);
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues)
    {
        return [];
    }

    /**
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
