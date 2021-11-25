<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function hasStopOnError(): bool;
}
