<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;

interface LifecycleMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    public function mapLifecycle(LifecycleInterface $lifecycle): PersistentLifecycleInterface;
}
