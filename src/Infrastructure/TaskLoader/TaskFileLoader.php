<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TaskLoader;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskCollection;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskFileLoader implements TaskLoaderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder
     */
    protected TaskBuilder $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface
     */
    protected TaskReaderInterface $taskFileReader;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder $taskBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\TaskReader\TaskReaderInterface $taskFileReader
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        TaskBuilder $taskBuilder,
        TaskFromYamlTaskSetBuilderInterface $taskSetBuilder,
        TaskRegistryInterface $taskRegistry,
        TaskReaderInterface $taskFileReader
    ) {
        $this->settingRepository = $settingRepository;
        $this->taskBuilder = $taskBuilder;
        $this->taskSetBuilder = $taskSetBuilder;
        $this->taskRegistry = $taskRegistry;
        $this->taskFileReader = $taskFileReader;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return array
     */
    public function loadAll(): array
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
            $taskYamlDto = new TaskYaml($taskData, $taskCollection->getTasks());
            $task = $this->taskBuilder->buildTaskByTaskYaml($taskYamlDto);
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
            $taskYamlDto = new TaskYaml($taskData, $taskCollection->getTasks(), $tasks);
            $task = $this->taskSetBuilder->buildTaskFromYamlTaskSet($taskYamlDto);
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

            $task = $this->taskBuilder->buildTaskByTaskSet($existingTask, $tasks);
            $this->taskRegistry->set($taskId, $task);
        }
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function loadOneById(string $taskId): ?TaskInterface
    {
        return $this->loadAll()[$taskId] ?? null;
    }
}
