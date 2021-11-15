<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Command
{
    /**
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     */
    public function __construct(
        public string $command,
        public string $type,
        public bool $hasStopOnError = true
    ) {
    }
}