<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
     * @var array
     */
    protected array $commands = [];

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle|null
     */
    protected ?Lifecycle $lifecycle = null;

    /**
     * @return bool[]|null[]|string[]||array[]
     */
    public function getScalarParts(): array
    {
        return $this->scalarParts;
    }

    /**
     * @param bool[]|null[]|string[]|array[] $scalarParts
     *
     * @return TaskYamlResultDto
     */
    public function setScalarParts(array $scalarParts): TaskYamlResultDto
    {
        $this->scalarParts = $scalarParts;

        return $this;
    }

    /**
     * @param string $name
     * @param string|bool|null|array $value
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     */
    public function addScalarPart(string $name, $value): TaskYamlResultDto
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
     * @return TaskYamlResultDto
     */
    public function setPlaceholders(array $placeholders): TaskYamlResultDto
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return $this
     */
    public function addPlaceholder(Placeholder $placeholder): TaskYamlResultDto
    {
        $this->placeholders[$placeholder->getName()] = $placeholder;

        return $this;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param array $commands
     *
     * @return TaskYamlResultDto
     */
    public function setCommands(array $commands): TaskYamlResultDto
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command): TaskYamlResultDto
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
     * @return TaskYamlResultDto
     */
    public function setLifecycle(Lifecycle $lifecycle): TaskYamlResultDto
    {
        $this->lifecycle = $lifecycle;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     */
    public function reset(): TaskYamlResultDto
    {
        $this->scalarParts = [];
        $this->placeholders = [];
        $this->commands = [];
        $this->lifecycle = null;

        return $this;
    }
}
