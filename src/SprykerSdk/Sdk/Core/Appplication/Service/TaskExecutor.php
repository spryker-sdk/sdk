<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\ErrorCommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;

class TaskExecutor
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface>
     */
    protected iterable $commandRunners;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
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
     * @param array $tags
     *
     * @return int
     */
    public function execute(string $taskId, array $tags = []): int
    {
        $task = $this->getTask($taskId, $tags);
        $resolvedValues = $this->getResolvedValues($task);

        foreach ($task->getCommands() as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $commandResponse = $commandRunner->execute($command, $resolvedValues);
                    $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, $commandResponse->getIsSuccessful()));

                    if (!$commandResponse->getIsSuccessful() && $command->hasStopOnError()) {
                        throw new CommandRunnerException($commandResponse->getErrorMessage());
                    }
                }
            }
        }

        return 0;
    }

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
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
