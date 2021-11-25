<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ExecutableCommandInterface;

class PhpCommandRunner implements CommandRunnerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'php' && $command instanceof ExecutableCommandInterface;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ExecutableCommandInterface $command
     * @param array $resolvedValues
     *
     * @return int
     */
    public function execute(CommandInterface $command, array $resolvedValues): int
    {
        return $command->execute($resolvedValues);
    }
}
