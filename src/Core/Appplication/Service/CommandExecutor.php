<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;

class CommandExecutor implements CommandExecutorInterface
{
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    protected iterable $commandRunners;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        iterable $commandRunners
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param callable|null $afterCommandExecutedCallback
     *
     * @return \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface
     */
    public function execute(array $commands, array $placeholders, ?callable $afterCommandExecutedCallback = null): CommandResponseInterface
    {
        $resolvedValues = $this->placeholderResolver->resolvePlaceholders($placeholders);

        $result = new CommandResponse(true);

        foreach ($commands as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if (!$commandRunner->canHandle($command)) {
                    continue;
                }

                $result = $commandRunner->execute($command, $resolvedValues);

                if ($afterCommandExecutedCallback) {
                    $afterCommandExecutedCallback($command, $result);
                }

                if (!$result->getIsSuccessful() && $command->hasStopOnError()) {
                    return $result;
                }
            }
        }

        return $result;
    }
}
