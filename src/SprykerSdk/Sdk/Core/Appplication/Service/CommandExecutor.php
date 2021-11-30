<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;

class CommandExecutor implements CommandExecutorInterface
{
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    protected iterable $commandRunners;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        iterable $commandRunners
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     * @param callable|null $loggerCallback
     *
     * @return int
     */
    public function execute(array $commands, array $placeholders, ?callable $loggerCallback = null): int
    {
        $resolvedValues = $this->placeholderResolver->resolvePlaceholders($placeholders);

        $result = 0;

        foreach ($commands as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $result = $commandRunner->execute($command, $resolvedValues);

                    if ($loggerCallback) {
                        $loggerCallback($command, $result);
                    }

                    if ($result !== 0 && $command->hasStopOnError()) {
                        return $result;
                    }
                }
            }
        }

        return $result;
    }
}
