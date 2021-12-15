<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class PhpCommandRunner implements CommandRunnerInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'php' && $command instanceof ExecutableCommandInterface;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface $command
     * @param array $resolvedValues
     *
     * @return \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface
     */
    public function execute(CommandInterface $command, array $resolvedValues): CommandResponseInterface
    {
        return $command->execute($resolvedValues);
    }
}
