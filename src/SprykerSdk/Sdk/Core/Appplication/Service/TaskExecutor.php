<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Events\TaskEvent;

class TaskExecutor
{
    /**
     * @param array<\SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface $eventLogger
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

        foreach ($task->commands as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $result = $commandRunner->execute($command, $resolvedValues);
                    $this->eventLogger->logEvent(new TaskEvent($task, $command, (bool)$result));

                    if ($result !== 0 && $command->hasStopOnError) {
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
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    protected function getTask(string $taskId): Task
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new TaskMissingException('Task not found with id ' . $taskId);
        }

        return $task;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Task $task
     *
     * @return array<string, mixed>
     */
    protected function getResolvedValues(Task $task): array
    {
        $resolvedValues = [];

        foreach ($task->placeholders as $placeholder) {
            $resolvedValues[$placeholder->name] = $this->placeholderResolver->resolve($placeholder);
        }

        return $resolvedValues;
    }
}