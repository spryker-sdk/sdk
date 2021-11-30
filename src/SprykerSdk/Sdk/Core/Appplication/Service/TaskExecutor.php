<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        CommandExecutorInterface $commandExecutor,
        EventLoggerInterface $eventLogger
    ) {
        $this->taskRepository = $taskRepository;
        $this->commandExecutor = $commandExecutor;
        $this->eventLogger = $eventLogger;
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

        $loggerCallback = function (CommandInterface $command, int $result) use ($task) {
            $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$result));
        };

        return $this->commandExecutor->execute($task->getCommands(), $task->getPlaceholders(), $loggerCallback);
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
}
