<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;

interface LifecycleMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle
     */
    public function mapLifecycle(LifecycleInterface $lifecycle): Lifecycle;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle $entityLifecycle
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle
     */
    public function updateLifecycle(LifecycleInterface $lifecycle, Lifecycle $entityLifecycle): Lifecycle;
}
