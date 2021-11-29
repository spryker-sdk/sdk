<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;

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
     *
     * @return \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface|null
     */
    public function get(string $id): ?ValueResolverInterface;
}
