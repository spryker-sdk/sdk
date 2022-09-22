<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Cache;

use SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;

class InMemoryContextCacheStorage implements ContextCacheStorageInterface
{
    /**
     * @var array<string, \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface>
     */
    protected array $contextStorage = [];

    /**
     * @param string $key
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface|null
     */
    public function get(string $key): ?ContextInterface
    {
        return $this->contextStorage[$key] ?? null;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface>
     */
    public function getAll(): array
    {
        return $this->contextStorage;
    }

    /**
     * @param string $key
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @return void
     */
    public function set(string $key, ContextInterface $context): void
    {
        $this->contextStorage[$key] = $context;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->contextStorage[$key]);
    }
}
