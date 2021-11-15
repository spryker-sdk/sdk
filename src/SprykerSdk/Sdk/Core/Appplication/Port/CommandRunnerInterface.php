<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Port;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;

interface CommandRunnerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Command $command
     *
     * @return bool
     */
    public function canHandle(Command $command): bool;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Command $command
     * @param array<string, mixed> $resolvedValues
     *
     * @return int
     */
    public function execute(Command $command, array $resolvedValues): int;
}