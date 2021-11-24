<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

class Lifecycle implements LifecycleInterface
{
    public function __construct(
        protected InitializedEvent $initializedEvent
    ) {
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventInterface
     */
    public function getInitialized(): LifecycleEventInterface
    {
        return $this->initializedEvent;
    }
}
