<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProgressBarInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
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
     * @param \SprykerSdk\SdkContracts\Logger\EventLoggerInterface $eventLogger
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
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface $commandResponse
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return void
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
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
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
