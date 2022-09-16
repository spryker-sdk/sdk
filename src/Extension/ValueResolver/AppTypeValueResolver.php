<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Extension\ValueResolver\Enum\Type;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class AppTypeValueResolver extends AbstractValueResolver
{
    /**
     * @var array
     */
    protected const REPOSITORIES = [
        'boilerplate' => 'https://github.com/spryker-projects/mini-framework',
    ];

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'APP_TYPE';
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
        return 'App template to use for creation';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return Type::STRING_TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return 'boilerplate_url';
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
