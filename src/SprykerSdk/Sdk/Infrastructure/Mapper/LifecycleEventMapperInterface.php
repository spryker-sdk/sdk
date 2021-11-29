<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;

interface LifecycleEventMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface $lifecycleEvent
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function mapRemovedEvent(LifecycleEventInterface $lifecycleEvent): RemovedEvent;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface $lifecycleEvent
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $removedEvent
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function updateRemovedEvent(LifecycleEventInterface $lifecycleEvent, RemovedEvent $removedEvent): RemovedEvent;
}
