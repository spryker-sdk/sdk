<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlFileLoader implements TaskYamlFileLoaderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

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
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage $taskStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface $taskBuilder
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder,
        InMemoryTaskStorage $taskStorage,
        TaskBuilderInterface $taskBuilder,
        iterable $existingTasks = []
    ) {
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
        $this->taskStorage = $taskStorage;
        $this->taskBuilder = $taskBuilder;
        foreach ($existingTasks as $existingTask) {
            $this->taskStorage->addTask($existingTask);
        }
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

        $taskCollectionData = [];
        $taskSetCollectionData = [];

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        //read task from path, parse and create Task, later use DB for querying
        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());

            if ($taskData['type'] === TaskType::TASK_TYPE__TASK_SET) {
                $taskSetCollectionData[$taskData['id']] = $taskData;

                continue;
            }

            $taskCollectionData[$taskData['id']] = $taskData;
        }

        foreach ($taskCollectionData as $taskData) {
            $task = $this->buildTask($taskData, $taskCollectionData);
            $this->taskStorage->addTask($task);
        }

        foreach ($taskSetCollectionData as $taskData) {
            $task = $this->buildTaskSet($taskData, $taskCollectionData);
            $this->taskStorage->addTask($task);
        }

        return $this->taskStorage->getTaskCollection();
    }

    /**
     * @param array<string> $directorySettings
     *
     * @return array<string>
     */
    protected function findExistedDirectories(array $directorySettings): array
    {
        $existingDirs = [];
        foreach ($directorySettings as $directorySetting) {
            $foundOldPaths = glob($directorySetting . '/Task');
            $foundNewPaths = glob($directorySetting . '/task');
            if ($foundOldPaths) {
                $existingDirs[] = $foundOldPaths;
            }
            if ($foundNewPaths) {
                $existingDirs[] = $foundNewPaths;
            }
        }

        return array_merge(...$existingDirs);
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
