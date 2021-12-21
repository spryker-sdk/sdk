<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;

interface LifecycleMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    public function mapLifecycle(TaskLifecycleInterface $lifecycle): PersistentLifecycleInterface;
}
