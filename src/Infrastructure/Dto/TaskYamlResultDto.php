<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskYamlResultDto
{
    /**
     * @var array<string|bool|null|array>
     */
    protected array $scalarParts = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface|null
     */
    protected ?LifecycleInterface $lifecycle = null;

    /**
     * @return array<array>|array<string>|array<bool>|array<null>
     */
    public function getScalarParts(): array
    {
        return $this->scalarParts;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getScalarPart(string $key, $default = null)
    {
        return array_key_exists($key, $this->scalarParts) ? $this->scalarParts[$key] : $default;
    }

    /**
     * @param array<array>|array<string>|array<bool>|array<null> $scalarParts
     *
     * @return void
     */
    public function setScalarParts(array $scalarParts): void
    {
        $this->scalarParts = $scalarParts;
    }

    /**
     * @param string $name
     * @param array|string|bool|null $value
     *
     * @return void
     */
    public function addScalarPart(string $name, $value): void
    {
        $this->scalarParts[$name] = $value;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return void
     */
    public function setPlaceholders(array $placeholders): void
    {
        $this->placeholders = $placeholders;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return void
     */
    public function addPlaceholder(PlaceholderInterface $placeholder): void
    {
        $this->placeholders[$placeholder->getName()] = $placeholder;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return void
     */
    public function addCommand(CommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface|null
     */
    public function getLifecycle(): ?LifecycleInterface
    {
        return $this->lifecycle;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface $lifecycle
     *
     * @return void
     */
    public function setLifecycle(LifecycleInterface $lifecycle): void
    {
        $this->lifecycle = $lifecycle;
    }
}
