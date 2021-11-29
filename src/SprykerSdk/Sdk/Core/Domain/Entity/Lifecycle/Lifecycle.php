<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
     * @param RemovedEvent $removedEvent
     * @param InitializedEvent $initializedEvent
     * @param UpdatedEvent $updatedEvent
     */
    public function __construct(InitializedEvent $initializedEvent, UpdatedEvent $updatedEvent, RemovedEvent $removedEvent)
    {
        $this->initializedEvent = $initializedEvent;
        $this->updatedEvent = $updatedEvent;
        $this->removedEvent = $removedEvent;
    }

    /**
     * @return RemovedEvent
     */
    public function getRemovedEvent(): RemovedEvent
    {
        return $this->removedEvent;
    }

    /**
     * @param RemovedEvent $removedEvent
     *
     * @return $this
     */
    public function setRemovedEvent(RemovedEvent $removedEvent): Lifecycle
    {
        $this->removedEvent = $removedEvent;

        return $this;
    }

    /**
     * @return InitializedEvent
     */
    public function getInitializedEvent(): InitializedEvent
    {
        return $this->initializedEvent;
    }

    /**
     * @param InitializedEvent $initializedEvent
     *
     * @return $this
     */
    public function setInitializedEvent(InitializedEvent $initializedEvent): Lifecycle
    {
        $this->initializedEvent = $initializedEvent;

        return $this;
    }

    /**
     * @return UpdatedEvent
     */
    public function getUpdatedEvent(): UpdatedEvent
    {
        return $this->updatedEvent;
    }

    /**
     * @param UpdatedEvent $updatedEvent
     * @return $this
     */
    public function setUpdatedEvent(UpdatedEvent $updatedEvent): Lifecycle
    {
        $this->updatedEvent = $updatedEvent;

        return $this;
    }
}
