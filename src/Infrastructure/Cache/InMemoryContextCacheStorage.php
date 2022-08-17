<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Cache;

use SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class FileContextCacheStorage implements ContextCacheStorageInterface
{

    public function get(string $contextName): ?ContextInterface
    {
        // TODO: Implement get() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function set(ContextInterface $context): void
    {
        // TODO: Implement set() method.
    }
}
