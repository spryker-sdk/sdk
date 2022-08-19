<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Cache;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface ContextCacheStorageInterface
{
    /**
     * @param string $key
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function get(string $key): ?ContextInterface;

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\ContextInterface>
     */
    public function getAll(): array;

    /**
     * @param string $key
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function set(string $key, ContextInterface $context): void;

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void;
}
