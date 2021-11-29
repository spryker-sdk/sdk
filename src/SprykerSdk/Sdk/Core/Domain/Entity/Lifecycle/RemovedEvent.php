<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

class RemovedEvent implements LifecycleEventInterface
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[]
     */
    protected array $commands = [];

    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[]
     */
    protected array $placeholders = [];

    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\FileInterface[]
     */
    protected array $files = [];

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[]
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\FileInterface[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[] $commands
     * @return RemovedEvent
     */
    public function setCommands(array $commands): RemovedEvent
    {
        $this->commands = $commands;
        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[] $placeholders
     * @return RemovedEvent
     */
    public function setPlaceholders(array $placeholders): RemovedEvent
    {
        $this->placeholders = $placeholders;
        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface[] $files
     * @return RemovedEvent
     */
    public function setFiles(array $files): RemovedEvent
    {
        $this->files = $files;
        return $this;
    }
}
