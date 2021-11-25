<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

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
     * @var array<\SprykerSdk\Sdk\Core\Domain\Entity\Command>
     */
    protected array $commands = [];

    /**
     * @var array<\SprykerSdk\Sdk\Core\Domain\Entity\Placeholder>
     */
    protected array $placeholders = [];

    /**
     * @var string|null
     */
    protected ?string $help = null;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Command> $commands
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Placeholder> $placeholders
     * @param string|null $help
     */
    public function __construct(
        string $id,
        string $shortDescription,
        array $commands,
        array $placeholders = [],
        ?string $help = null
    ) {
        $this->help = $help;
        $this->placeholders = $placeholders;
        $this->commands = $commands;
        $this->shortDescription = $shortDescription;
        $this->id = $id;
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
}
