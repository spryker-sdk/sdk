<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use JsonSerializable;

interface MessageInterface extends JsonSerializable
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

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return int
     */
    public function getVerbosity(): int;

    /**
     * @return array
     */
    public function jsonSerialize(): array;
}
