<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Task as CoreTask;

class Task extends CoreTask
{
    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected Collection $placeholderCollection;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected Collection $commandCollection;

    protected PersistentLifecycleInterface $lifecycle;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface $lifecycle
     * @param string $version
     * @param string|null $help
     * @param string|null $successor
     * @param bool $isDeprecated
     */
    public function __construct(
        string $id,
        string $shortDescription,
        PersistentLifecycleInterface $lifecycle,
        string $version,
        ?string $help = null,
        ?string $successor = null,
        bool $isDeprecated = false
    ) {
        $this->commandCollection = new ArrayCollection();
        $this->placeholderCollection = new ArrayCollection();

        parent::__construct($id, $shortDescription, [], $lifecycle, $version, [], $help, $successor, $isDeprecated);
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    public function getLifecycle(): PersistentLifecycleInterface
    {
        return $this->lifecycle;
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

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholderCollection->toArray();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return $this
     */
    public function setPlaceholders(Collection $placeholders)
    {
        $this->placeholderCollection = $placeholders;

        return $this;
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
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commandCollection->toArray();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int, \SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     *
     * @return $this
     */
    public function setCommands(Collection $commands)
    {
        $this->commandCollection = $commands;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Command $command
     *
     * @return $this
     */
    public function addCommand(Command $command)
    {
        if (!$this->commandCollection->contains($command)) {
            $this->commandCollection[] = $command;
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
        if ($this->commandCollection->contains($command)) {
            $this->commandCollection->removeElement($command);
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
        if (!$this->placeholderCollection->contains($placeholder)) {
            $this->placeholderCollection[] = $placeholder;
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
        if ($this->placeholderCollection->contains($placeholder)) {
            $this->placeholderCollection->removeElement($placeholder);
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
}
