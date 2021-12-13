<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandResponseInterface;

class CommandResponse implements CommandResponseInterface
{
    /**
     * @var bool
     */
    public bool $isSuccessful;

    /**
     * @var int
     */
    public int $code;

    /**
     * @var string|null
     */
    public ?string $errorMessage;

    /**
     * @param bool $isSuccessful
     * @param int $code
     * @param string|null $errorMessage
     */
    public function __construct(bool $isSuccessful, int $code = 0, ?string $errorMessage = null)
    {
        $this->isSuccessful = $isSuccessful;
        $this->code = $code;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return bool
     */
    public function getIsSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @param bool $isSuccessful
     *
     * @return $this
     */
    public function setIsSuccessful(bool $isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return void
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
