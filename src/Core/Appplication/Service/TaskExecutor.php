<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaggedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface>
     */
    protected iterable $commandRunners;

    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected ViolationConverterResolver $violationConverterResolver;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver $violationConverterResolver
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        CommandExecutorInterface $commandExecutor,
        ViolationConverterResolver $violationConverterResolver
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->taskRepository = $taskRepository;
        $this->commandExecutor = $commandExecutor;
        $this->violationConverterResolver = $violationConverterResolver;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(string $taskId, ContextInterface $context): ContextInterface
    {
        $context = $this->addBaseTask($taskId, $context);
        $context = $this->collectTasks($context);
        $context = $this->collectAvailableStages($context);
        $context = $this->resolveInputStages($context);
        $context = $this->collectRequiredPlaceholders($context);
        $context = $this->resolveValues($context);

        return $this->executeTasks($context);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function resolveValues(ContextInterface $context): ContextInterface
    {
        $existingValues = $context->getResolvedValues();
        $overwrites = $context->getOverwrites();

        foreach ($context->getRequiredPlaceholders() as $requiredPlaceholder) {
            //the placeholder might already be resolved when the context was passed from a previous stage
            if (!$this->isValueResolved($requiredPlaceholder, $existingValues, $overwrites)) {
                $context->addResolvedValues(
                    $requiredPlaceholder->getName(),
                    $this->placeholderResolver->resolve($requiredPlaceholder, $context),
                );
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $stage
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function executeStage(ContextInterface $context, ?string $stage = null): ContextInterface
    {
        $stageTasks = $context->getSubTasks();

        if ($stage !== null) {
            $stageTasks = array_filter($context->getSubTasks(), function (TaskInterface $task) use ($stage): bool {
                return ($task instanceof StagedTaskInterface && $task->getStage() === $stage);
            });

            if (count($stageTasks) > 0) {
                $context->addMessage(
                    $context->getTask()->getId(),
                    new Message('Executing stage ' . $stage, MessageInterface::DEBUG),
                );
            }

            $context->setSubTasks($stageTasks);
        }

        foreach ($stageTasks as $task) {
            //execute stage as sub process

            foreach ($task->getCommands() as $command) {
                $context = $this->commandExecutor->execute($command, $context, $task->getId());

                $this->addViolation($context, $command);

                if ($context->getExitCode() !== 0 && $command->hasStopOnError()) {
                    return $context;
                }
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function addViolation(ContextInterface $context, CommandInterface $command): ContextInterface
    {
        $violationConverter = $this->violationConverterResolver->resolve($command);

        if (!$violationConverter) {
            return $context;
        }

        $violationReport = $violationConverter->convert();

        if (!$violationReport) {
            return $context;
        }

        $context->addViolationReport($violationReport);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
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

        if ($context->getRequiredStages() != ContextInterface::DEFAULT_STAGES) {
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
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

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function resolveInputStages(ContextInterface $context): ContextInterface
    {
        $task = $context->getTask();

        $requiredStages = array_intersect($context->getInputStages(), $context->getAvailableStages());

        if (
            $task instanceof TaskSetInterface &&
            count($context->getInputStages()) === 0 &&
            count($task->getStages()) !== 0
        ) {
            $requiredStages = $task->getStages();
        }

        if (empty($requiredStages)) {
            $context->setRequiredStages(ContextInterface::DEFAULT_STAGES);

            return $context;
        }

        $context->setRequiredStages($requiredStages);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $requiredPlaceholder
     * @param array<string, mixed> $existingValues
     * @param array<string> $overwrites
     *
     * @return bool
     */
    protected function isValueResolved(
        PlaceholderInterface $requiredPlaceholder,
        array $existingValues,
        array $overwrites
    ): bool {
        if (!array_key_exists($requiredPlaceholder->getName(), $existingValues)) {
            return false;
        }

        $valueResolver = $this->placeholderResolver->getValueResolver($requiredPlaceholder);

        if (in_array($valueResolver->getAlias(), $overwrites) || in_array($valueResolver->getId(), $overwrites)) {
            return false;
        }

        return true;
    }
}
