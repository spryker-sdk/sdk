<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml;

use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskYamlFileLoader implements TaskYamlFileLoaderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $taskStorage;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector
     */
    protected TaskYamlCollector $taskYamlCollector;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector $taskYamlCollector
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $taskStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface $taskBuilder
     */
    public function __construct(
        TaskYamlCollector $taskYamlCollector,
        TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder,
        TaskStorage $taskStorage,
        TaskBuilderInterface $taskBuilder
    ) {
        $this->taskYamlCollector = $taskYamlCollector;
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
        $this->taskStorage = $taskStorage;
        $this->taskBuilder = $taskBuilder;
    }

    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function loadAll(): array
    {
        $manifestCollection = $this->taskYamlCollector->collectAll();

        foreach ($manifestCollection->getTasks() as $taskData) {
            $task = $this->buildTask($taskData, $manifestCollection->getTasks());
            $this->taskStorage->addTask($task);
        }

        $taskCollection = $this->taskStorage->getTaskCollection();

        foreach ($manifestCollection->getTaskSets() as $taskData) {
            $task = $this->buildTaskSet($taskData, $manifestCollection->getTasks(), $taskCollection);
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
     * @param array<string, mixed> $taskData
     * @param array<string, mixed> $taskListData
     * @param array $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function buildTaskSet(array $taskData, array $taskListData, array $existingTasks): TaskInterface
    {
        return $this->taskFromYamlTaskSetBuilder->buildTaskFromYamlTaskSet($taskData, $taskListData, $existingTasks);
    }
}
