<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;

class TaskExecutor
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface>
     */
    protected iterable $commandRunners;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param array<\SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface $eventLogger
     */
    public function __construct(
        iterable $commandRunners,
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        EventLoggerInterface $eventLogger
    ) {
        $this->eventLogger = $eventLogger;
        $this->taskRepository = $taskRepository;
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
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
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface
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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface $task
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
