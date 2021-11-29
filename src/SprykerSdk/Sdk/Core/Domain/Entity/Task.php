<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;

class Task implements TaskInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $shortDescription;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders = [];

    /**
     * @var string|null
     */
    protected ?string $help = null;

    protected string $version;

    protected ?string $successor = null;

    protected bool $deprecated = false;

    protected LifecycleInterface $lifecycle;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     * @param LifecycleInterface $lifecycle
     * @param string $version
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     * @param string|null $help
     * @param string|null $successor
     * @param bool $deprecated
     */
    public function __construct(
        string $id,
        string $shortDescription,
        array $commands,
        LifecycleInterface $lifecycle,
        string $version,
        array $placeholders = [],
        ?string $help = null,
        ?string $successor = null,
        bool $deprecated = false,
    ) {
        $this->help = $help;
        $this->placeholders = $placeholders;
        $this->commands = $commands;
        $this->shortDescription = $shortDescription;
        $this->id = $id;
        $this->version = $version;
        $this->successor = $successor;
        $this->deprecated = $deprecated;
        $this->lifecycle = $lifecycle;
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
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
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
        return $this->deprecated;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface|null
     */
    public function getLifecycle(): LifecycleInterface
    {
        return $this->lifecycle;
    }
}
