<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

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
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders = [];

    /**
     * @var string|null
     */
    protected ?string $help = null;

    protected string $version;

    protected ?string $successor = null;

    protected bool $isDeprecated;

    protected LifecycleInterface $lifecycle;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     * @param string $version
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param string|null $help
     * @param string|null $successor
     * @param bool $isDeprecated
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
        bool $isDeprecated = false
    ) {
        $this->help = $help;
        $this->placeholders = $placeholders;
        $this->commands = $commands;
        $this->shortDescription = $shortDescription;
        $this->id = $id;
        $this->version = $version;
        $this->successor = $successor;
        $this->isDeprecated = $isDeprecated;
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
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
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
        return $this->isDeprecated;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface
    {
        return $this->lifecycle;
    }
}
