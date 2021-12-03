<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;
use SprykerSdk\Sdk\Contracts\Entity\MessageInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaggedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskSetInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    public function execute(string $taskId, ContextInterface $context): ContextInterface
    {
        $context = $this->addBaseTask($taskId, $context);
        $context = $this->collectAvailableStages($context);
        $context = $this->collectTasks($context);
        $context = $this->collectRequiredPlaceholders($context);
        $context = $this->resolveValues($context);

        return $this->executeTasks($context);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function collectRequiredPlaceholders(ContextInterface $context): ContextInterface
    {
        foreach ($context->getSubTasks() as $task) {
            foreach ($task->getPlaceholders() as $placeholder) {
                $context->addRequiredPlaceholder($placeholder);
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function resolveValues(ContextInterface $context): ContextInterface
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function executeTasks(ContextInterface $context): ContextInterface
    {
        foreach ($context->getRequiredStages() as $stage) {
            $context = $this->executeStage($context, $stage);

            if ($context->getExitCode() !== 0) {
                return $context;
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function executeCommand(CommandInterface $command, ContextInterface $context, TaskInterface $task): ContextInterface
    {
        foreach ($this->commandRunners as $commandRunner) {
            if (!$commandRunner->canHandle($command)) {
                continue;
            }

            if ($context->isDryRun()) {
                $context->addMessage(new Message(sprintf(
                    'Run: %s (class: %s, command runner: %s, will stop on error: %s)',
                    $command->getCommand(),
                    $command::class,
                    $commandRunner::class,
                    $command->hasStopOnError() ? 'yes' : 'no',
                ), MessageInterface::DEBUG));

                continue;
            }

            $context = $commandRunner->execute($command, $context);
            $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$context->getExitCode()));

            return $context;
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     * @param string|null $stage
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function executeStage(ContextInterface $context, ?string $stage = null): ContextInterface
    {
        $stageTasks = $context->getSubTasks();

        if ($stage !== null) {
            $stageTasks = array_filter($context->getSubTasks(), function (TaskInterface $task) use ($stage): bool {
                return ($task instanceof StagedTaskInterface && $task->getStage() === $stage);
            });

            if (count($stageTasks) > 0) {
                $context->addMessage(new Message('Executing stage ' . $stage, MessageInterface::DEBUG));
            }
        }

        foreach ($stageTasks as $task) {
            //execute stage as sub process


            foreach ($task->getCommands() as $command) {
                $context = $this->executeCommand($command, $context, $task);

                //@todo if command has a report convert, use it to add report to the context
                //$context->addViolationReport($command->getReportConverter()->convert());

                if ($context->getExitCode() !== 0 && $command->hasStopOnError()) {
                    return $context;
                }
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function collectTasks(ContextInterface $context): ContextInterface
    {
        $task = $context->getTask();

        if (!$task instanceof TaskSetInterface) {
            $context->setSubTasks([$task]);

            return $context;
        }

        $subTasks = array_filter($task->getSubTasks(), function (TaskInterface $task) use ($context): bool {
            if (!$task instanceof TaggedTaskInterface) {
                return false;
            }

            return count(array_intersect($task->getTags(), $context->getTags())) > 0;
        });

        if (!empty($context->getRequiredStages()) && $context->getRequiredStages() != ContextInterface::DEFAULT_STAGES) {
            $subTasks = array_filter($subTasks, function (TaskInterface $task) use ($context): bool {
                if (!$task instanceof StagedTaskInterface) {
                    return false;
                }

                return in_array($task->getStage(), $context->getRequiredStages());
            });
        }

        $context->setSubTasks($subTasks);

        return $context;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function addBaseTask(string $taskId, ContextInterface $context): ContextInterface
    {
        $baseTask = $this->taskRepository->findById($taskId, $context->getTags());

        if (!$baseTask) {
            throw new TaskMissingException(sprintf('No task with id %s found', $taskId));
        }

        $context->setTask($baseTask);

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function collectAvailableStages(ContextInterface $context): ContextInterface
    {
        if (count($context->getSubTasks()) < 1) {
            $mainTask = $context->getTask();
            $availableStage = $mainTask instanceof StagedTaskInterface ? $mainTask->getStage() : ContextInterface::DEFAULT_STAGE;

            $context->setAvailableStages([$availableStage]);
            $context->setRequiredStages([$availableStage]);

            return $context;
        }

        $availableStages = array_unique(
            array_map(function (TaskInterface $subTask): string {
                if ($subTask instanceof StagedTaskInterface) {
                    return $subTask->getStage();
                }

                return ContextInterface::DEFAULT_STAGE;
            }, $context->getSubTasks()),
        );

        $context->setAvailableStages($availableStages);

        if ($context->getRequiredStages() === ContextInterface::DEFAULT_STAGES) {
            $context->setRequiredStages($context->getAvailableStages());
        }

        return $context;
    }
}
