<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting;

use InvalidArgumentException;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use Traversable;

class SettingChoicesProviderRegistry
{
    /**
     * @var array<\SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface>
     */
    protected array $settingChoicesProviders;

    /**
     * @param iterable<\SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface> $settingChoicesProviders
     */
    public function __construct(iterable $settingChoicesProviders)
    {
        $this->settingChoicesProviders = $settingChoicesProviders instanceof Traversable
            ? iterator_to_array($settingChoicesProviders)
            : $settingChoicesProviders;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSettingChoicesProvider(string $name): bool
    {
        return isset($this->settingChoicesProviders[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface
     */
    public function getSettingChoicesProvider(string $name): SettingChoicesProviderInterface
    {
        if (!isset($this->settingChoicesProviders[$name])) {
            throw new InvalidArgumentException(sprintf('Choices provider `%s` is not found', $name));
        }

        return $this->settingChoicesProviders[$name];
    }
}
