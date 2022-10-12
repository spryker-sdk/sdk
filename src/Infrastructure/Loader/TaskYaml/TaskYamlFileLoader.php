<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskSetManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    protected ManifestValidatorInterface $manifestValidator;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader $taskYamlReader
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage $taskStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface $taskBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface $manifestValidator
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        TaskYamlReader $taskYamlReader,
        TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder,
        InMemoryTaskStorage $taskStorage,
        TaskBuilderInterface $taskBuilder,
        ManifestValidatorInterface $manifestValidator,
        iterable $existingTasks = []
    ) {
        $this->taskYamlReader = $taskYamlReader;
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
        $this->taskStorage = $taskStorage;
        $this->taskBuilder = $taskBuilder;
        foreach ($existingTasks as $existingTask) {
            $this->taskStorage->addTask($existingTask);
        }
        $this->manifestValidator = $manifestValidator;
    }

    /**
     * @return array
     */
    public function loadAll(): array
    {
        $manifestCollection = $this->taskYamlReader->readFiles();

        $manifestCollection->setTasks(
            $this->manifestValidator->validate(TaskManifestConfiguration::NAME, $manifestCollection->getTasks()),
        );
        $manifestCollection->setTaskSets(
            $this->manifestValidator->validate(TaskSetManifestConfiguration::NAME, $manifestCollection->getTaskSets()),
        );

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
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function loadById(string $taskId): ?TaskInterface
    {
        return $this->loadAll()[$taskId] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $taskId
     * @param bool $includeTaskSet
     *
     * @return bool
     */
    public function isTaskIdExist(string $taskId, bool $includeTaskSet = true): bool
    {
        $manifestCollection = $this->taskYamlReader->readFiles();

        return isset($manifestCollection->getTasks()[$taskId]) ||
            $this->taskStorage->getTaskById($taskId) ||
            (isset($manifestCollection->getTaskSets()[$taskId]) && $includeTaskSet);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string> $taskIds
     * @param bool $includeTaskSet
     *
     * @return array<string, array<string>>
     */
    public function getTaskPlaceholders(array $taskIds, bool $includeTaskSet = false): array
    {
        $manifestCollection = $this->taskYamlReader->readFiles();

        $taskPlaceholders = [];
        foreach ($taskIds as $taskId) {
            if (isset($manifestCollection->getTasks()[$taskId])) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (array $placeholder) {
                        return $placeholder['name'];
                    },
                    $manifestCollection->getTasks()[$taskId]['placeholders'],
                );

                continue;
            }

            if ($this->taskStorage->getTaskById($taskId)) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (PlaceholderInterface $placeholder) {
                        return $placeholder->getName();
                    },
                    $this->taskStorage->getTaskById($taskId)->getPlaceholders(),
                );

                continue;
            }

            if ($includeTaskSet && isset($manifestCollection->getTaskSets()[$taskId])) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (array $placeholder) {
                        return $placeholder['name'];
                    },
                    $manifestCollection->getTaskSets()[$taskId]['placeholders'],
                );
            }
        }

        return $taskPlaceholders;
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
