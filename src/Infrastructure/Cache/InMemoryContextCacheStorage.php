<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Cache;

use SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class InMemoryContextCacheStorage implements ContextCacheStorageInterface
{
    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\ContextInterface>
     */
    protected array $contextStorage = [];

    /**
     * @param string $key
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function get(string $key): ?ContextInterface
    {
        return $this->contextStorage[$key] ?? null;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\ContextInterface>
     */
    public function getAll(): array
    {
        return $this->contextStorage;
    }

    /**
     * @param string $key
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function set(string $key, ContextInterface $context): void
    {
        $this->contextStorage[$key] = $context;

        if ($key !== ContextCacheStorageInterface::KEY_LAST) {
            $this->contextStorage[ContextCacheStorageInterface::KEY_LAST] = $context;
        }
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        if ($this->contextStorage[$key] === $this->contextStorage[ContextCacheStorageInterface::KEY_LAST]) {
            unset($this->contextStorage[$key], $this->contextStorage[ContextCacheStorageInterface::KEY_LAST]);

            return;
        }

        unset($this->contextStorage[$key]);
    }
}
