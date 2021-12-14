<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface;

interface LifecycleMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    public function mapLifecycle(LifecycleInterface $lifecycle): PersistentLifecycleInterface;
}
