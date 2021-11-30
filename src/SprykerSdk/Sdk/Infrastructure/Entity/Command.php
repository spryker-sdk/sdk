<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Command as CoreCommand;

class Command extends CoreCommand
{
    protected int $id;

    protected ?Task $task = null;

    protected RemovedEvent $removedEvent;

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
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task|null $task
     *
     * @return $this;
     */
    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task|null
     */
    public function getTask(): ?Task
    {
        return $this->task;
    }
}
