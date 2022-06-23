<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class CommandExecutor implements CommandExecutorInterface
{
    /**
     * @var iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    protected iterable $commandRunners;

    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface>
     */
    protected iterable $afterCommandExecutedActions;

    /**
     * @param iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface> $afterCommandExecutedActions
     */
    public function __construct(
        iterable $commandRunners,
        iterable $afterCommandExecutedActions = []
    ) {
        $this->commandRunners = $commandRunners;
        $this->afterCommandExecutedActions = $afterCommandExecutedActions;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        foreach ($this->commandRunners as $commandRunner) {
            if (!$commandRunner->canHandle($command)) {
                continue;
            }

            if ($context->isDryRun()) {
                $message = new Message(sprintf(
                    'Run: %s (class: %s, command runner: %s, will stop on error: %s)',
                    $command->getCommand(),
                    get_class($command),
                    get_class($commandRunner),
                    $command->hasStopOnError() ? 'yes' : 'no',
                ), MessageInterface::DEBUG);

                $context->addMessage($command->getCommand(), $message);

                continue;
            }

            $context = $commandRunner->execute($command, $context);
            $context->addExitCode($command->getCommand(), $context->getExitCode());

            return $this->executeAfterCommandExecutedActions($command, $context);
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function executeAfterCommandExecutedActions(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        foreach ($this->afterCommandExecutedActions as $afterCommandExecutedAction) {
            $context = $afterCommandExecutedAction->execute($command, $context);
        }

        return $context;
    }
}
