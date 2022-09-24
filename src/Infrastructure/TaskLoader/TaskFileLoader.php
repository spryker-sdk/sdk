<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TaskLoader;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskCollection;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskFileLoader implements TaskLoaderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface
     */
    protected TaskYamlFactoryInterface $taskYamlFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface
     */
    protected TaskReaderInterface $taskFileReader;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface $taskBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskSetBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface $taskYamlFactory
     * @param \SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface $taskFileReader
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        TaskBuilderInterface $taskBuilder,
        TaskFromYamlTaskSetBuilderInterface $taskSetBuilder,
        TaskRegistryInterface $taskRegistry,
        TaskYamlFactoryInterface $taskYamlFactory,
        TaskReaderInterface $taskFileReader
    ) {
        $this->settingRepository = $settingRepository;
        $this->taskBuilder = $taskBuilder;
        $this->taskSetBuilder = $taskSetBuilder;
        $this->taskRegistry = $taskRegistry;
        $this->taskYamlFactory = $taskYamlFactory;
        $this->taskFileReader = $taskFileReader;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return array
     */
    public function findAll(): array
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('extension_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('extension_dirs are not configured properly');
        }

        $tasks = [];
        $taskCollection = $this->taskFileReader->read($taskDirSetting);

        $tasks = $this->collectTasks($taskCollection, $tasks);
        $tasks = $this->collectTaskSets($taskCollection, $tasks);

        $this->updateTaskRegistryWithTaskSetTasks($tasks);

        return array_merge($tasks, $this->taskRegistry->getAll());
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskCollection $taskCollection
     * @param array<string, \SprykerSdk\Sdk\Core\Domain\Entity\Task> $tasks
     *
     * @return array<string, \SprykerSdk\Sdk\Core\Domain\Entity\Task>
     */
    protected function collectTasks(TaskCollection $taskCollection, array $tasks): array
    {
        foreach ($taskCollection->getTasks() as $taskData) {
            $task = $this->taskBuilder->buildTask(
                $this->taskYamlFactory->createTaskYaml($taskData, $taskCollection->getTasks()),
            );
            $tasks[$task->getId()] = $task;
        }

        return $tasks;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskCollection $taskCollection
     * @param array<string, \SprykerSdk\Sdk\Core\Domain\Entity\Task> $tasks
     *
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected function collectTaskSets(TaskCollection $taskCollection, array $tasks): array
    {
        foreach ($taskCollection->getTaskSets() as $taskData) {
            $task = $this->taskSetBuilder->buildTaskFromYamlTaskSet(
                $this->taskYamlFactory->createTaskYaml($taskData, $taskCollection->getTasks(), $tasks),
            );
            $tasks[$task->getId()] = $task;
        }

        return $tasks;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return void
     */
    protected function updateTaskRegistryWithTaskSetTasks(array $tasks): void
    {
        foreach ($this->taskRegistry->getAll() as $taskId => $existingTask) {
            if (!$existingTask instanceof TaskSetInterface) {
                continue;
            }

            $this->taskRegistry->set($taskId, new Task(
                $existingTask->getId(),
                $existingTask->getShortDescription(),
                $this->extractCommands($tasks, $existingTask),
                $existingTask->getLifecycle(),
                $existingTask->getVersion(),
                $this->extractPlaceholders($tasks, $existingTask),
                $existingTask->getHelp(),
                $existingTask->getSuccessor(),
                $existingTask->isDeprecated(),
                ContextInterface::DEFAULT_STAGE,
                $existingTask->isOptional(),
                $existingTask->getStages(),
            ));
        }
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $existingTask
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function extractPlaceholders(array $tasks, TaskSetInterface $existingTask): array
    {
        $placeholders = [];
        foreach ($existingTask->getSubTasks() as $subTask) {
            if (is_string($subTask)) {
                $subTask = $tasks[$subTask] ?? $this->taskRegistry->get($subTask);
            }
            $placeholders[] = $subTask->getPlaceholders();
        }

        return array_merge(...$placeholders);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $existingTask
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function extractCommands(array $tasks, TaskSetInterface $existingTask): array
    {
        $commands = [];

        foreach ($existingTask->getSubTasks() as $subTask) {
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
                $command instanceof ExecutableCommandInterface || $command->getType() === 'php' ?
                    get_class($command) :
                    $command->getCommand(),
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
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface
    {
        return $this->findAll()[$taskId] ?? null;
    }
}
