<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

class TaskYamlResultDto
{
    /**
     * @var array<string|bool|null|array>
     */
    protected array $scalarParts = [];

    /**
     * @var array
     */
    protected array $placeholders = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle|null
     */
    protected ?Lifecycle $lifecycle = null;

    /**
     * @return array<array>|array<string>|array<bool>|array<null>
     */
    public function getScalarParts(): array
    {
        return $this->scalarParts;
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
     * @return array
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @param array $placeholders
     *
     * @return $this
     */
    public function setPlaceholders(array $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return $this
     */
    public function addPlaceholder(Placeholder $placeholder)
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
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle|null
     */
    public function getLifecycle(): ?Lifecycle
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
     * @return $this
     */
    public function reset()
    {
        $this->scalarParts = [];
        $this->placeholders = [];
        $this->commands = [];
        $this->lifecycle = null;

        return $this;
    }
}
