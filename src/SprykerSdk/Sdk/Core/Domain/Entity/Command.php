<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

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
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     */
    public function __construct(
        string $command,
        string $type,
        bool $hasStopOnError = true
    ) {
        $this->hasStopOnError = $hasStopOnError;
        $this->type = $type;
        $this->command = $command;
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
