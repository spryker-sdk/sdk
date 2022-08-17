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
     * @param string $contextName
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function get(string $contextName): ?ContextInterface
    {
        return $this->contextStorage[$contextName] ?? null;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\ContextInterface>
     */
    public function getAll(): array
    {
        return $this->contextStorage;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function set(ContextInterface $context): void
    {
        $this->contextStorage[$context->getName()] = $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function remove(ContextInterface $context): void
    {
        unset($this->contextStorage[$context->getName()]);
    }
}
