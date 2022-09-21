<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting;

use InvalidArgumentException;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;
use Traversable;

class SettingInitializerRegistry
{
    /**
     * @var array<string, \SprykerSdk\SdkContracts\Setting\SettingInitializerInterface>
     */
    private array $settingInitializers;

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Setting\SettingInitializerInterface> $settingInitializers
     */
    public function __construct(iterable $settingInitializers)
    {
        $this->settingInitializers = $settingInitializers instanceof Traversable
            ? iterator_to_array($settingInitializers)
            : $settingInitializers;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSettingInitializer(string $name): bool
    {
        return isset($this->settingInitializers[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\SdkContracts\Setting\SettingInitializerInterface
     */
    public function getSettingInitializer(string $name): SettingInitializerInterface
    {
        if (!isset($this->settingInitializers[$name])) {
            throw new InvalidArgumentException(sprintf('Initializer `%s` is not found', $name));
        }

        return $this->settingInitializers[$name];
    }
}
