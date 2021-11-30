<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
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
     * @param \Doctrine\Common\Collections\Collection $placeholders
     *
     * @return $this
     */
    public function setPlaceholders(Collection $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $commands
     *
     * @return $this
     */
    public function setCommands(Collection $commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $files
     *
     * @return $this
     */
    public function setFiles(Collection $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return $this
     */
    public function addCommand(Command $command)
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
     * @return $this
     */
    public function removeCommand(Command $command)
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
     * @return $this
     */
    public function addPlaceholder(Placeholder $placeholder)
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
     * @return $this
     */
    public function removePlaceholder(Placeholder $placeholder)
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
     * @return $this
     */
    public function addFile(File $file)
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
     * @return $this
     */
    public function removeFile(File $file)
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
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
