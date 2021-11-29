<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

class RemovedEvent implements LifecycleEventInterface
{
    protected int $id;

    protected Collection $placeholders;

    protected Collection $commands;

    protected Collection $files;

    public function __construct()
    {
        $this->placeholders = new ArrayCollection();
        $this->commands = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getCommands(): array
    {
        return $this->commands->toArray();
    }

    public function getPlaceholders(): array
    {
        return $this->placeholders->toArray();
    }

    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @param Collection $placeholders
     * @return RemovedEvent
     */
    public function setPlaceholders(Collection $placeholders): RemovedEvent
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param Collection $commands
     * @return RemovedEvent
     */
    public function setCommands(Collection $commands): RemovedEvent
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param Collection $files
     * @return RemovedEvent
     */
    public function setFiles(Collection $files): RemovedEvent
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function addCommand(Command $command): RemovedEvent
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setRemovedEvent($this);
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function removeCommand(Command $command): RemovedEvent
    {
        if ($this->commands->contains($command)) {
            $this->commands->removeElement($command);

            if ($command->getRemovedEvent() === $this) {
                $command->setRemovedEvent($this);
            }
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function addPlaceholder(Placeholder $placeholder): RemovedEvent
    {
        if (!$this->placeholders->contains($placeholder)) {
            $this->placeholders[] = $placeholder;
            $placeholder->setRemovedEvent($this);
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function removePlaceholder(Placeholder $placeholder): RemovedEvent
    {
        if ($this->placeholders->contains($placeholder)) {
            $this->placeholders->removeElement($placeholder);

            if ($placeholder->getRemovedEvent() === $this) {
                $placeholder->setRemovedEvent($this);
            }
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\File $file
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function addFile(File $file): RemovedEvent
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setRemovedEvent($this);
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\File $file
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function removeFile(File $file): RemovedEvent
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);

            if ($file->getRemovedEvent() === $this) {
                $file->setRemovedEvent($this);
            }
        }

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
     * @return RemovedEvent
     */
    public function setId(int $id): RemovedEvent
    {
        $this->id = $id;

        return $this;
    }
}
