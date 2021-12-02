<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var int
     */
    public const DEBUG = 1;

    /**
     * @var int
     */
    public const INFO = 2;

    /**
     * @var int
     */
    public const SUCCESS = 3;

    /**
     * @var int
     */
    public const ERROR = 4;

    protected string $message;

    protected int $verbosity;

    /**
     * @param string $message
     * @param int $verbosity
     */
    public function __construct(string $message, int $verbosity = self::INFO)
    {
        $this->message = $message;
        $this->verbosity = $verbosity;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'verbosity' => $this->verbosity,
        ];
    }
}
