<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

class Task implements TaskInterface
{
    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected Collection $placeholders;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected Collection $commands;

    protected Lifecycle $lifecycle;

    protected string $id;

    protected string $shortDescription;

    protected string $version;

    protected ?string $help = null;

    protected ?string $successor = null;

    protected bool $isDeprecated = false;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param string $version
     * @param string|null $help
     * @param string|null $successor
     * @param bool $isDeprecated
     */
    public function __construct(
        string $id,
        string $shortDescription,
        string $version,
        ?string $help = null,
        ?string $successor = null,
        bool $isDeprecated = false
    ) {
        $this->commands = new ArrayCollection();
        $this->placeholders = new ArrayCollection();
        $this->id = $id;
        $this->shortDescription = $shortDescription;
        $this->version = $version;
        $this->help = $help;
        $this->successor = $successor;
        $this->isDeprecated = $isDeprecated;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle
     */
    public function getLifecycle(): Lifecycle
    {
        return $this->lifecycle;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders->toArray();
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
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }

    /**
     * @param string|null $help
     *
     * @return $this
     */
    public function setHelp(?string $help)
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
     *
     * @return $this
     */
    public function setId(string $id)
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
     *
     * @return $this
     */
    public function setShortDescription(string $shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
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
        return $this->isDeprecated;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands->toArray();
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
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return $this
     */
    public function addCommand(Command $command)
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
     * @return $this
     */
    public function removeCommand(Command $command)
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
     * @return $this
     */
    public function addPlaceholder(Placeholder $placeholder)
    {
        if (!$this->placeholders->contains($placeholder)) {
            $this->placeholders[] = $placeholder;
            $placeholder->setTask($this);
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

            if ($placeholder->getTask() === $this) {
                $placeholder->setTask($this);
            }
        }

        return $this;
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param string|null $successor
     *
     * @return $this
     */
    public function setSuccessor(?string $successor)
    {
        $this->successor = $successor;

        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return $this
     */
    public function setIsDeprecated(bool $isDeprecated)
    {
        $this->isDeprecated = $isDeprecated;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle $lifecycle
     *
     * @return $this
     */
    public function setLifecycle(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;

        return $this;
    }
}
