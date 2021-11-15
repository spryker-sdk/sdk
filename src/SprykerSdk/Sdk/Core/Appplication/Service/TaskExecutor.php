<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Port\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;

class TaskExecutor
{
    /**
     * @param array<\SprykerSdk\Sdk\Core\Appplication\Port\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Port\EventLoggerInterface $eventLogger
     */
    public function __construct(
        protected iterable $commandRunners,
        protected PlaceholderResolver $placeholderResolver,
        protected TaskRepositoryInterface $taskRepository,
        protected EventLoggerInterface $eventLogger
    ) {
    }

    /**
     * @param string $taskId
     *
     * @return int
     */
    public function execute(string $taskId): int
    {
        $task = $this->taskRepository->findById($taskId);

        //@todo error handling task not found
        if (!$task) {
            throw new \RuntimeException('Task not found');
        }

        $resolvedValues = [];

        foreach ($task->placeholders as $placeholder) {
            $resolvedValues[$placeholder->name] = $this->placeholderResolver->resolve($placeholder);
        }

        $result = 0;

        foreach ($task->commands as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $result = $commandRunner->execute($command, $resolvedValues);

                    if ($result !== 0 && $command->hasStopOnError) {
                        return $result;
                    }
                }
            }
        }

        return $result;
    }
}