<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Command as CoreCommand;

class Command extends CoreCommand
{
    protected int $id;

    protected ?Task $task = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Task $task
     *
     * @return $this;
     */
    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @return Task
     */
    public function getTask(): ?Task
    {
        return $this->task;
    }
}
