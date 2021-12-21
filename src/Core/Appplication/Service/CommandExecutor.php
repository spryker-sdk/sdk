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
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    protected iterable $commandRunners;

    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface>
     */
    protected iterable $afterCommandExecutedActions;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface> $afterCommandExecutedActions
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        iterable $commandRunners,
        iterable $afterCommandExecutedActions = []
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
        $this->afterCommandExecutedActions = $afterCommandExecutedActions;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string $subTaskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context, string $subTaskId): ContextInterface
    {
        foreach ($this->commandRunners as $commandRunner) {
            if (!$commandRunner->canHandle($command)) {
                continue;
            }

            if ($context->isDryRun()) {
                $message = new Message(sprintf(
                    'Run: %s (class: %s, command runner: %s, will stop on error: %s)',
                    $command->getCommand(),
                    $command::class,
                    $commandRunner::class,
                    $command->hasStopOnError() ? 'yes' : 'no',
                ), MessageInterface::DEBUG);

                $context->addMessage($command->getCommand(), $message);

                continue;
            }

            $context = $commandRunner->execute($command, $context);
            $context->addExitCode($command->getCommand(), $context->getExitCode());

            return $this->executeAfterCommandExecutedActions($command, $context, $subTaskId);
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string $subTaskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function executeAfterCommandExecutedActions(CommandInterface $command, ContextInterface $context, string $subTaskId): ContextInterface
    {
        foreach ($this->afterCommandExecutedActions as $afterCommandExecutedAction) {
            $context = $afterCommandExecutedAction->execute($command, $context, $subTaskId);
        }

        return $context;
    }
}
