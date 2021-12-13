<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use Symfony\Component\Console\Output\OutputInterface;

class PhpCommandRunner implements CommandRunnerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'php' && $command instanceof ExecutableCommandInterface;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface $command
     * @param array $resolvedValues
     *
     * @return CommandResponseInterface
     */
    public function execute(CommandInterface $command, array $resolvedValues): CommandResponseInterface
    {
        return $command->execute($resolvedValues);
    }
}
