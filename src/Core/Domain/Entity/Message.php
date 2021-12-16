<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\MessageInterface;

class Message implements MessageInterface
{
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
}
