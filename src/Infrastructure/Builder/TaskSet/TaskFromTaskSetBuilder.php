<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskFromTaskSetBuilder implements TaskFromTaskSetBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetPlaceholdersBuilder
     */
    protected TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder
     */
    protected TaskSetCommandsBuilder $taskSetCommandsBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory
     */
    protected TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder $taskSetCommandsBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory
     */
    public function __construct(
        TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder,
        TaskSetCommandsBuilder $taskSetCommandsBuilder,
        TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory
    ) {
        $this->taskSetPlaceholdersBuilder = $taskSetPlaceholdersBuilder;
        $this->taskSetCommandsBuilder = $taskSetCommandsBuilder;
        $this->taskSetOverrideMapFactory = $taskSetOverrideMapFactory;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskFromTaskSet(TaskSetInterface $taskSet, array $existingTasks): TaskInterface
    {
        $taskSetOverrideMap = $this->taskSetOverrideMapFactory->createFromTaskSet($taskSet);

        $placeholders = $this->taskSetPlaceholdersBuilder->buildTaskSetPlaceholders(
            $this->getSubTasksPlaceholders($taskSet, $existingTasks),
            $taskSetOverrideMap,
        );

        $commands = $this->taskSetCommandsBuilder->buildTaskSetCommands(
            $this->getSubTasksCommands($taskSet, $existingTasks),
            $taskSetOverrideMap,
        );

        return new Task(
            $taskSet->getId(),
            $taskSet->getShortDescription(),
            $commands,
            $taskSet->getLifecycle(),
            $taskSet->getVersion(),
            $placeholders,
            $taskSet->getHelp(),
            $taskSet->getSuccessor(),
            $taskSet->isDeprecated(),
            ContextInterface::DEFAULT_STAGE,
            $taskSet->isOptional(),
            $taskSet->getStages(),
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\CommandInterface>>
     */
    protected function getSubTasksCommands(TaskSetInterface $taskSet, array $existingTasks): array
    {
        $subTasksCommands = [];

        foreach ($taskSet->getSubTasks() as $task) {
            if (is_string($task)) {
                $task = $this->getTaskFromTaskList($task, $existingTasks);
            }

            foreach ($task->getCommands() as $command) {
                $subTasksCommands[$task->getId()][] = $command;
            }
        }

        return $subTasksCommands;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>>
     */
    protected function getSubTasksPlaceholders(TaskSetInterface $taskSet, array $existingTasks): array
    {
        $subTasksPlaceholders = [];

        foreach ($taskSet->getSubTasks() as $task) {
            if (is_string($task)) {
                $task = $this->getTaskFromTaskList($task, $existingTasks);
            }

            foreach ($task->getPlaceholders() as $placeholder) {
                $subTasksPlaceholders[$task->getId()][] = $placeholder;
            }
        }

        return $subTasksPlaceholders;
    }

    /**
     * @param string $taskId
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getTaskFromTaskList(string $taskId, array $existingTasks): TaskInterface
    {
        if (!isset($existingTasks[$taskId])) {
            throw new InvalidArgumentException(sprintf('TaskYaml %s not found', $taskId));
        }

        return $existingTasks[$taskId];
    }
}
