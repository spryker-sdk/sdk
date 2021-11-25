<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;

class Task implements TaskInterface
{
    /**
     * @param string $id
     * @param string $shortDescription
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Command> $commands
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Placeholder> $placeholders
     * @param string|null $help
     * @param string|null $version
     * @param string|null $successor
     * @param bool $deprecated
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface|null $lifecycle
     */
    public function __construct(
        protected string $id,
        protected string $shortDescription,
        protected array $commands,
        protected array $placeholders = [],
        protected ?string $help = null,
        protected ?string $version = null,
        protected ?string $successor = null,
        protected bool $deprecated = false,
        protected ?LifecycleInterface $lifecycle = null
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @return CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return PlaceholderInterface[]
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
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

    public function getLifecycle(): ?LifecycleInterface
    {
        return $this->lifecycle;
    }
}
