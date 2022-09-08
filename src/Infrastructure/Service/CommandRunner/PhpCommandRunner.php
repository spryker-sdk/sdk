<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        /**
         * @var \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
         */
        $context = $command->execute($context);

        if (
            $context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE &&
            $command instanceof ErrorCommandInterface &&
            strlen($command->getErrorMessage())
        ) {
            $context->addMessage(
                $command->getCommand(),
                new Message($command->getErrorMessage(), MessageInterface::ERROR),
            );
        }

        return $context;
    }
}
