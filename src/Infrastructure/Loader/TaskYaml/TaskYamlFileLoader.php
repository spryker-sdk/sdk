<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml;

use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskYamlFileLoader implements TaskYamlFileLoaderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader
     */
    protected TaskYamlReader $taskYamlReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage
     */
    protected InMemoryTaskStorage $taskStorage;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader $taskYamlReader
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage $taskStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface $taskBuilder
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        TaskYamlReader $taskYamlReader,
        TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder,
        InMemoryTaskStorage $taskStorage,
        TaskBuilderInterface $taskBuilder,
        iterable $existingTasks = []
    ) {
        $this->taskYamlReader = $taskYamlReader;
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
        $this->taskStorage = $taskStorage;
        $this->taskBuilder = $taskBuilder;
        foreach ($existingTasks as $existingTask) {
            $this->taskStorage->addTask($existingTask);
        }
    }

    /**
     * @return array
     */
    public function loadAll(): array
    {
        $manifestCollection = $this->taskYamlReader->readFiles();

        foreach ($manifestCollection->getTasks() as $taskData) {
            $task = $this->buildTask($taskData, $manifestCollection->getTasks());
            $this->taskStorage->addTask($task);
        }

        foreach ($manifestCollection->getTaskSets() as $taskData) {
            $task = $this->buildTaskSet($taskData, $manifestCollection->getTasks());
            $this->taskStorage->addTask($task);
        }

        return $this->taskStorage->getTaskCollection();
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function buildTask(array $taskData, array $taskListData): TaskInterface
    {
        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            $taskListData,
        );

        return $this->taskBuilder->build($criteriaDto);
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function buildTaskSet(array $taskData, array $taskListData): TaskInterface
    {
        return $this->taskFromYamlTaskSetBuilder->buildTaskFromYamlTaskSet($taskData, $taskListData);
    }
}
