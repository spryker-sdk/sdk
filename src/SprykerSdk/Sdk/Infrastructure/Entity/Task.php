<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;

class Task implements TaskInterface
{
    protected Collection $placeholders;

    protected Collection $commands;

    protected ?LifecycleInterface $lifecycle = null;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param string|null $help
     * @param string|null $version
     * @param string|null $successor
     * @param bool $deprecated
     */
    public function __construct(
        protected string $id,
        protected string $shortDescription,
        protected ?string $help = null,
        protected ?string $version = null,
        protected ?string $successor = null,
        protected bool $deprecated = false,
    ) {
        $this->commands = new ArrayCollection();
        $this->placeholders = new ArrayCollection();
    }

    /**
     * @return LifecycleInterface|null
     */
    public function getLifecycle(): ?LifecycleInterface
    {
        return $this->lifecycle;
    }

    /**
     * @return PlaceholderInterface[]
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders->toArray();
    }

    /**
     * @param Collection $placeholders
     * @return Task
     */
    public function setPlaceholders(Collection $placeholders): Task
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }

    /**
     * @param string|null $help
     * @return Task
     */
    public function setHelp(?string $help): Task
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Task
     */
    public function setId(string $id): Task
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     * @return Task
     */
    public function setShortDescription(string $shortDescription): Task
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return $this->successor;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands->toArray();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $commands
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function setCommands(Collection $commands): Task
    {
        $this->commands = $commands;
        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function addCommand(Command $command): Task
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setTask($this);
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function removeCommand(Command $command): Task
    {
        if ($this->commands->contains($command)) {
            $this->commands->removeElement($command);

            if ($command->getTask() === $this) {
                $command->setTask($this);
            }
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function addPlaceholder(Placeholder $placeholder): Task
    {
        if (!$this->placeholders->contains($placeholder)) {
            $this->placeholders[] = $placeholder;
            $placeholder->setTask($this);
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function removePlaceholder(Placeholder $placeholder): Task
    {
        if ($this->placeholders->contains($placeholder)) {
            $this->placeholders->removeElement($placeholder);

            if ($placeholder->getTask() === $this) {
                $placeholder->setTask($this);
            }
        }

        return $this;
    }
}
