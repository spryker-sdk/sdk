<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;

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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    public function execute(CommandInterface $command, Context $context): Context
    {
        return $command->execute($context);
    }
}
