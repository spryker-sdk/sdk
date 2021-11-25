<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\CommandRunner;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;

interface CommandRunnerInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool;

    /**
     * @param CommandInterface $command
     * @param array<string, mixed> $resolvedValues
     *
     * @return int
     */
    public function execute(CommandInterface $command, array $resolvedValues): int;
}
