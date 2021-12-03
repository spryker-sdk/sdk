<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\ProgressBar\ProgressBarInterface;
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
     * @var \SprykerSdk\Sdk\Contracts\ProgressBar\ProgressBarInterface
     */
    protected ProgressBarInterface $progressBar;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
     * @param \SprykerSdk\Sdk\Contracts\ProgressBar\ProgressBarInterface $progressBar
     */
    public function __construct(
        iterable $commandRunners,
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        EventLoggerInterface $eventLogger,
        ProgressBarInterface $progressBar
    ) {
        $this->eventLogger = $eventLogger;
        $this->taskRepository = $taskRepository;
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
        $this->progressBar = $progressBar;
    }

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return int
     */
    public function execute(string $taskId, array $tags = []): int
    {
        $task = $this->getTask($taskId, $tags);
        $resolvedValues = $this->getResolvedValues($task);

        $result = true;

        $countExecutableCommands = 0;
        foreach ($task->getCommands() as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $countExecutableCommands++;
                }
            }
        }

        $this->progressBar->start();
        foreach ($task->getCommands() as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $commandResponse = $commandRunner->execute($command, $resolvedValues);

                    $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, $commandResponse->getIsSuccessful()));

                    $result = $commandResponse->getIsSuccessful();
                    if (!$result && $command->hasStopOnError()) {
                        $this->progressBar->setMessage((string)$commandResponse->getErrorMessage());
                        $this->progressBar->finish();

                        throw new CommandRunnerException((string)$commandResponse->getErrorMessage());
                    }

                    $this->progressBar->advance();
                }
            }
        }

        $this->progressBar->finish();

        return (int)$result;
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
