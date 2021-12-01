<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Contracts\ValueResolver\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;

class PbcTypeValueResolver extends AbstractValueResolver
{
    protected const REPOSITORIES = [
        'boilerplate' => 'https://github.com/spryker/project-boilerplate',
    ];
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'PBC_TYPE';
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional = false, array $resolvedValues = []): mixed
    {
        $value = parent::getValue($settingValues, $optional, $resolvedValues);

        return static::REPOSITORIES[$value];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Input based repository for new PBC';
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
        return 'boilerplate_url';
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
    public function getDefaultValue(): mixed
    {
        return array_key_first(static::REPOSITORIES);
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
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
