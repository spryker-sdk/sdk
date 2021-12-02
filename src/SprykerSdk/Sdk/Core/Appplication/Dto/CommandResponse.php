<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto;

class CommandResponse
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
     * @var string
     */
    public string $errorMessage;

    /**
     * @param bool $isSuccessful
     * @param int $code
     * @param string $errorMessage
     */
    public function __construct(bool $isSuccessful, int $code = 0, string $errorMessage = '')
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
    public function setIsSuccessful(bool $isSuccessful): self
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
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessages
     *
     * @return string
     */
    public function setErrorMessage(string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
