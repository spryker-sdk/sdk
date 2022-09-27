<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder
     */
    protected PlaceholderBuilder $placeholderBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder
     */
    protected CommandBuilder $commandBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder
     */
    protected LifecycleBuilder $lifecycleBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder $placeholderBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder $commandBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder $lifecycleBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface $taskRegistry
     */
    public function __construct(
        PlaceholderBuilder $placeholderBuilder,
        CommandBuilder $commandBuilder,
        LifecycleBuilder $lifecycleBuilder,
        TaskRegistryInterface $taskRegistry
    ) {
        $this->placeholderBuilder = $placeholderBuilder;
        $this->commandBuilder = $commandBuilder;
        $this->lifecycleBuilder = $lifecycleBuilder;
        $this->taskRegistry = $taskRegistry;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTaskByTaskYaml(TaskYaml $taskYaml): Task
    {
        $placeholders = $this->placeholderBuilder->buildPlaceholders($taskYaml);
        $commands = $this->commandBuilder->buildCommands($taskYaml);
        $lifecycle = $this->lifecycleBuilder->buildLifecycle($taskYaml);

        $taskData = $taskYaml->getTaskData();

        return new Task(
            $taskData['id'],
            $taskData['short_description'],
            $commands,
            $lifecycle,
            $taskData['version'],
            $placeholders,
            $taskData['help'] ?? null,
            $taskData['successor'] ?? null,
            $taskData['deprecated'] ?? false,
            $taskData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            !empty($taskData['optional']),
            $taskData['stages'] ?? [],
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskByTaskSet(TaskSetInterface $taskSet, array $tasks): TaskInterface
    {
        return new Task(
            $taskSet->getId(),
            $taskSet->getShortDescription(),
            $this->extractCommands($tasks, $taskSet),
            $taskSet->getLifecycle(),
            $taskSet->getVersion(),
            $this->extractPlaceholders($tasks, $taskSet),
            $taskSet->getHelp(),
            $taskSet->getSuccessor(),
            $taskSet->isDeprecated(),
            ContextInterface::DEFAULT_STAGE,
            $taskSet->isOptional(),
            $taskSet->getStages(),
        );
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function extractCommands(array $tasks, TaskSetInterface $taskSet): array
    {
        $commands = [];

        foreach ($taskSet->getSubTasks() as $subTask) {
            if (is_string($subTask)) {
                $subTask = $tasks[$subTask] ?? $this->taskRegistry->get($subTask);
            }
            $commands[] = $this->extractExistingCommands($subTask);
        }

        return array_merge(...$commands);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function extractExistingCommands(TaskInterface $task): array
    {
        $commands = [];
        foreach ($task->getCommands() as $command) {
            $commands[] = new Command(
                $this->extractCommandStringFromCommand($command),
                $command->getType(),
                $command->hasStopOnError(),
                $command->getTags(),
                $command->getConverter(),
                $task instanceof StagedTaskInterface ? $task->getStage() : $command->getStage(),
                $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
            );
        }

        return $commands;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function extractPlaceholders(array $tasks, TaskSetInterface $taskSet): array
    {
        $placeholders = [];
        foreach ($taskSet->getSubTasks() as $subTask) {
            if (is_string($subTask)) {
                $subTask = $tasks[$subTask] ?? $this->taskRegistry->get($subTask);
            }
            $placeholders[] = $subTask->getPlaceholders();
        }

        return array_merge(...$placeholders);
    }

    /**
     * @param mixed $command
     *
     * @return string
     */
    protected function extractCommandStringFromCommand($command): string
    {
        return $command instanceof ExecutableCommandInterface || $command->getType() === TaskType::TYPE_PHP_TASK
            ? get_class($command)
            : $command->getCommand();
    }
}
