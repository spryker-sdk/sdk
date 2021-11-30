<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;

class Lifecycle implements LifecycleInterface
{
    protected InitializedEvent $initializedEvent;

    protected UpdatedEvent $updatedEvent;

    protected RemovedEvent $removedEvent;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent $removedEvent
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent $initializedEvent
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent $updatedEvent
     */
    public function __construct(InitializedEvent $initializedEvent, UpdatedEvent $updatedEvent, RemovedEvent $removedEvent)
    {
        $this->initializedEvent = $initializedEvent;
        $this->updatedEvent = $updatedEvent;
        $this->removedEvent = $removedEvent;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent
     */
    public function getRemovedEvent(): RemovedEvent
    {
        return $this->removedEvent;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent $removedEvent
     *
     * @return $this
     */
    public function setRemovedEvent(RemovedEvent $removedEvent)
    {
        $this->removedEvent = $removedEvent;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent
     */
    public function getInitializedEvent(): InitializedEvent
    {
        return $this->initializedEvent;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent $initializedEvent
     *
     * @return $this
     */
    public function setInitializedEvent(InitializedEvent $initializedEvent)
    {
        $this->initializedEvent = $initializedEvent;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent
     */
    public function getUpdatedEvent(): UpdatedEvent
    {
        return $this->updatedEvent;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent $updatedEvent
     *
     * @return $this
     */
    public function setUpdatedEvent(UpdatedEvent $updatedEvent)
    {
        $this->updatedEvent = $updatedEvent;

        return $this;
    }
}
