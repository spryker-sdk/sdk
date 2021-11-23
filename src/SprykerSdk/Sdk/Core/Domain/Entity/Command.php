<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Command implements CommandInterface
{
    /**
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     * @param array<string> $tags
     */
    public function __construct(
        protected string $command,
        protected string $type,
        protected bool $hasStopOnError = true,
        protected array $tags = []
    ) {
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
     * @return string[]
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
