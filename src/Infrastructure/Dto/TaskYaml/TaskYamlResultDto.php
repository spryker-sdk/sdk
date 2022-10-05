<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml;

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
     * @return $this
     */
    public function setScalarParts(array $scalarParts)
    {
        $this->scalarParts = $scalarParts;

        return $this;
    }

    /**
     * @param string $name
     * @param array|string|bool|null $value
     *
     * @return $this
     */
    public function addScalarPart(string $name, $value)
    {
        $this->scalarParts[$name] = $value;

        return $this;
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
     * @return $this
     */
    public function setPlaceholders(array $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return $this
     */
    public function addPlaceholder(PlaceholderInterface $placeholder)
    {
        $this->placeholders[$placeholder->getName()] = $placeholder;

        return $this;
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
     * @return $this
     */
    public function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;

        return $this;
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
     * @return $this
     */
    public function setLifecycle(LifecycleInterface $lifecycle)
    {
        $this->lifecycle = $lifecycle;

        return $this;
    }
}
