<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface ValueResolverRegistryInterface
{
    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * @param string $id
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface|null
     */
    public function get(string $id): ?ValueResolverInterface;
}