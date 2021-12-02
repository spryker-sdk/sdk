<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaggedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskSetInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface>
     */
    protected iterable $commandRunners;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
     * @param array<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        EventLoggerInterface $eventLogger,
        iterable $commandRunners
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->taskRepository = $taskRepository;
        $this->eventLogger = $eventLogger;
        $this->commandRunners = $commandRunners;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    public function execute(string $taskId, Context $context): Context
    {
        $context = $this->collectTasks($context, $taskId);
        $context = $this->collectRequiredPlaceholders($context);
        $context = $this->resolveValues($context);

        return $this->executeTasks($context);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function collectRequiredPlaceholders(Context $context): Context
    {
        foreach ($context->getTasks() as $task) {
            $context->addTask($task);

            foreach ($task->getPlaceholders() as $placeholder) {
                $context->addRequiredPlaceholder($placeholder);
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function resolveValues(Context $context): Context
    {
        $existingValues = $context->getResolvedValues();

        foreach ($context->getRequiredPlaceholders() as $requiredPlaceholder) {
            //the placeholder might already be resolved when the context was passed from a previous stage
            if (!array_key_exists($requiredPlaceholder->getName(), $existingValues)) {
                $context->addResolvedValues(
                    $requiredPlaceholder->getName(),
                    $this->placeholderResolver->resolve($requiredPlaceholder, $context),
                );
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function executeTasks(Context $context): Context
    {
        $tasks = $context->getTasks();
        $stages = $context->getStages();

        foreach ($stages as $stage) {
            $context = $this->executeStage($context, $tasks, $stage);

            if ($context->getResult() !== 0) {
                return $context;
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function executeCommand(CommandInterface $command, Context $context, TaskInterface $task): Context
    {
        foreach ($this->commandRunners as $commandRunner) {
            if ($commandRunner->canHandle($command)) {
                $context = $commandRunner->execute($command, $context);
                $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$context->getResult()));

                return $context;
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     * @param array $tasks
     * @param string|null $stage
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function executeStage(Context $context, array $tasks, ?string $stage = null): Context
    {
        $stageTasks = $tasks;

        if ($stage !== null) {
            $stageTasks = array_filter($tasks, function (TaskInterface $task) use ($stage): bool {
                return (!$task instanceof StagedTaskInterface || $task->getStage() !== $stage);
            });

            if (count($stageTasks) > 0) {
                $context->addMessage(new Message('Executing stage ' . $stage, Message::DEBUG));
            }
        }

        foreach ($stageTasks as $task) {
            foreach ($task->getCommands() as $command) {
                $context = $this->executeCommand($command, $context, $task);

                //@todo if command has a report convert, use it to add report to the context
                //$context->addViolationReport($command->getReportConverter()->convert());

                if ($context->getResult() !== 0 && $command->hasStopOnError()) {
                    return $context;
                }
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     * @param string $taskId
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function collectTasks(Context $context, string $taskId): Context
    {
        $task = $this->taskRepository->findById($taskId, $context->getTags());

        if (!$task) {
            throw new TaskMissingException(sprintf('No task with id %s found', $taskId));
        }

        $tasks = [$task];

        if ($task instanceof TaskSetInterface) {
            $tasks = $task->getTasks();
        }

        if (!empty($context->getTasks())) {
            $tasks = array_filter($tasks, function (TaskInterface $task) use ($context): bool {
                if (!$task instanceof TaggedTaskInterface) {
                    return false;
                }

                return count(array_intersect($task->getTags(), $context->getTags())) > 0;
            });
        }

        if (!empty($context->getStages()) && $context->getStages() != [Context::DEFAULT_STAGE]) {
            $tasks = array_filter($tasks, function (TaskInterface $task) use ($context): bool {
                if (!$task instanceof StagedTaskInterface) {
                    return false;
                }

                return in_array($task->getStage(), $context->getStages());
            });
        }

        $context->setTasks($tasks);

        return $context;
    }
}
