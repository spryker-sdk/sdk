<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepository implements TaskYamlRepositoryInterface
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
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilderInterface
     */
    protected TaskSetBuilderInterface $taskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskPool
     */
    protected TaskPool $taskPool;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface $taskBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilderInterface $taskSetBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskPool $taskPool
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        TaskBuilderInterface $taskBuilder,
        TaskSetBuilderInterface $taskSetBuilder,
        TaskPool $taskPool
    ) {
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
        $this->taskBuilder = $taskBuilder;
        $this->taskSetBuilder = $taskSetBuilder;
        $this->taskPool = $taskPool;
    }

    /**
     * @param array $tags
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return array
     */
    public function findAll(array $tags = []): array
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('extension_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('extension_dirs are not configured properly');
        }

        $tasks = [];
        $taskListData = [];
        $taskSetsData = [];

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        //read task from path, parse and create Task, later use DB for querying
        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());

            if ($taskData['type'] === TaskType::TASK_SET_TYPE) {
                $taskSetsData[$taskData['id']] = $taskData;
            } else {
                $taskListData[$taskData['id']] = $taskData;
            }
        }

        foreach ($taskListData as $taskData) {
            $task = $this->taskBuilder->buildTask($taskData, $taskListData, $tags);
            $tasks[$task->getId()] = $task;
        }

        foreach ($taskSetsData as $taskData) {
            $task = $this->taskSetBuilder->buildTaskSet($taskData, $taskListData, $tasks, $tags);
            $tasks[$task->getId()] = $task;
        }

        return array_merge($tasks, $this->taskPool->getTasks());
    }

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId, array $tags = []): ?TaskInterface
    {
        $tasks = $this->findAll($tags);

        if (array_key_exists($taskId, $tasks)) {
            return $tasks[$taskId];
        }

        return null;
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
            $found = glob($directorySetting . '/{Task,task}', GLOB_BRACE);
            if ($found) {
                $existingDirs[] = $found;
            }
        }

        return array_merge(...$existingDirs);
    }
}
