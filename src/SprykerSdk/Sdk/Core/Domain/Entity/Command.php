<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;

class Command implements CommandInterface
{
    /**
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     */
    public function __construct(
        protected string $command,
        protected string $type,
        protected bool $hasStopOnError = true
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
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return $this->hasStopOnError;
    }
}
