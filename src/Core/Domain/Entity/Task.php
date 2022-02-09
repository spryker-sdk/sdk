<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;

class Task implements StagedTaskInterface
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

    /**
     * @var string
     */
    protected string $version;

    /**
     * @var string|null
     */
    protected ?string $successor = null;

    /**
     * @var bool
     */
    protected bool $isDeprecated;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface
     */
    protected LifecycleInterface $lifecycle;

    /**
     * @var string
     */
    protected string $stage = ContextInterface::DEFAULT_STAGE;

    /**
     * @var bool
     */
    protected bool $optional = false;

    /**
     * @var array<string>
     */
    protected array $stages = [];

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
     * @param string $stage
     * @param bool|false $optional
     * @param array<string> $stages
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
        bool $isDeprecated = false,
        string $stage = ContextInterface::DEFAULT_STAGE,
        bool $optional = false,
        array $stages = []
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
        $this->stage = $stage;
        $this->optional = $optional;
        $this->stages = $stages;
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

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @param bool $optional
     *
     * @return $this
     */
    public function setOptional(bool $optional)
    {
        $this->optional = $optional;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    /**
     * @param array<string> $stages
     *
     * @return $this
     */
    public function setStages(array $stages)
    {
        $this->stages = $stages;

        return $this;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     *
     * @return $this
     */
    public function setCommandsArray(array $commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return $this
     */
    public function setPlaceholdersArray(array $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }
}
