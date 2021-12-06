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

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected Collection $placeholders;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected Collection $commands;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\FileInterface>
     */
    protected Collection $files;

    public function __construct()
    {
        $this->placeholders = new ArrayCollection();
        $this->commands = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands->toArray();
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders->toArray();
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface>
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return $this
     */
    public function setPlaceholders(Collection $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     *
     * @return $this
     */
    public function setCommands(Collection $commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\FileInterface> $files
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
