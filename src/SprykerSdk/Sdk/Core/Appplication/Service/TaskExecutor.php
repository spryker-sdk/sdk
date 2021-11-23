<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;

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
     * @param array $tags
     *
     * @return int
     */
    public function execute(string $taskId, array $tags = []): int
    {
        $task = $this->getTask($taskId, $tags);
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
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface
     */
    protected function getTask(string $taskId, array $tags = []): TaskInterface
    {
        $task = $this->taskRepository->findById($taskId, $tags);

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
