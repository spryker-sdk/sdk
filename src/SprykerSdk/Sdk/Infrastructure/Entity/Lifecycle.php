<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;

class Lifecycle implements PersistentLifecycleInterface
{
    protected int $id;

    protected RemovedEvent $removedEvent;

    /**
     * @param RemovedEvent $removedEvent
     */
    public function __construct(RemovedEvent $removedEvent)
    {
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Lifecycle
    {
        $this->id = $id;

        return $this;
    }
}
