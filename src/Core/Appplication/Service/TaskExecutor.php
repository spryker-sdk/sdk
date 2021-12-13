<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProgressBarInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProgressBarInterface
     */
    protected ProgressBarInterface $progressBar;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProgressBarInterface $progressBar
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        CommandExecutorInterface $commandExecutor,
        EventLoggerInterface $eventLogger,
        ProgressBarInterface $progressBar
    ) {
        $this->taskRepository = $taskRepository;
        $this->commandExecutor = $commandExecutor;
        $this->eventLogger = $eventLogger;
        $this->progressBar = $progressBar;
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

        $afterCommandExecutedCallback = function (CommandInterface $command, CommandResponseInterface $commandResponse) use ($task): void {
            $this->afterCommandExecuted($command, $commandResponse, $task);
        };

        $this->progressBar->start();

        $result = $this->commandExecutor->execute($task->getCommands(), $task->getPlaceholders(), $afterCommandExecutedCallback);

        $this->progressBar->finish();

        return $result->getCode();
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param CommandResponseInterface $commandResponse
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     */
    public function afterCommandExecuted(CommandInterface $command, CommandResponseInterface $commandResponse, TaskInterface $task): void
    {
        $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$commandResponse->getCode()));

        $result = $commandResponse->getIsSuccessful();
        if (!$result && $command->hasStopOnError()) {
            $this->progressBar->setMessage((string)$commandResponse->getErrorMessage());
            $this->progressBar->finish();

            throw new CommandRunnerException((string)$commandResponse->getErrorMessage());
        }

        $this->progressBar->advance();
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
