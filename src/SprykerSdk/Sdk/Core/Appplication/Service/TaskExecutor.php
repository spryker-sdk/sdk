<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;

class TaskExecutor
{
    /**
     * @param array<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
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
        $task = $this->getTask($taskId);
        $resolvedValues = $this->getResolvedValues($task);

        $result = 0;

        foreach ($task->getCommands() as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $result = $commandRunner->execute($command, $resolvedValues);
                    $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$result));

                    if ($result !== 0 && $command->hasStopOnError()) {
                        return $result;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param string $taskId
     *
     * @return TaskInterface
     */
    protected function getTask(string $taskId): TaskInterface
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new TaskMissingException('Task not found with id ' . $taskId);
        }

        return $task;
    }

    /**
     * @param TaskInterface $task
     *
     * @return array<string, mixed>
     */
    protected function getResolvedValues(TaskInterface $task): array
    {
        $resolvedValues = [];

        foreach ($task->getPlaceholders() as $placeholder) {
            $resolvedValues[$placeholder->getName()] = $this->placeholderResolver->resolve($placeholder);
        }

        return $resolvedValues;
    }
}
