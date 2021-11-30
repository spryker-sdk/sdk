<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;

class Lifecycle implements PersistentLifecycleInterface
{
    protected int $id;

    protected RemovedEvent $removedEvent;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $removedEvent
     */
    public function __construct(RemovedEvent $removedEvent)
    {
        $this->removedEvent = $removedEvent;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function getRemovedEvent(): RemovedEvent
    {
        return $this->removedEvent;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $removedEvent
     *
     * @return $this
     */
    public function setRemovedEvent(RemovedEvent $removedEvent)
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
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
