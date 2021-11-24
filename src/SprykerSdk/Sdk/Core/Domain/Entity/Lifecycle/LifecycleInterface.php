<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

interface LifecycleInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventInterface
     */
    public function getInitialized(): ?LifecycleEventInterface;
}
