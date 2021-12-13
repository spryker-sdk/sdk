<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\CommandInterface;

class Command implements CommandInterface
{
    /**
     * @var string
     */
    protected string $command;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var bool
     */
    protected bool $hasStopOnError = true;

    /**
     * @var array<string>
     */
    protected array $tags;

    /**
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     * @param array<string> $tags
     */
    public function __construct(
        string $command,
        string $type,
        bool $hasStopOnError = true,
        array $tags = []
    ) {
        $this->hasStopOnError = $hasStopOnError;
        $this->type = $type;
        $this->command = $command;
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return $this->hasStopOnError;
    }
}
