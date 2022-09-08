<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilderInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface
     */
    protected TaskPoolInterface $taskPool;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface $taskBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilderInterface $taskSetBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface $taskPool
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        TaskBuilderInterface $taskBuilder,
        TaskSetBuilderInterface $taskSetBuilder,
        TaskPoolInterface $taskPool
    ) {
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
        $this->taskBuilder = $taskBuilder;
        $this->taskSetBuilder = $taskSetBuilder;
        $this->taskPool = $taskPool;
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
            $task = $this->taskBuilder->buildTask(new TaskYaml($taskData, $taskListData));
            $tasks[$task->getId()] = $task;
        }

        foreach ($taskSetsData as $taskData) {
            $task = $this->taskSetBuilder->buildTaskSet(new TaskYaml($taskData, $taskListData, $tasks));
            $tasks[$task->getId()] = $task;
        }

        $this->extractTaskSetTasks($tasks);

        return array_merge($tasks, $this->taskPool->getAll());
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return void
     */
    protected function extractTaskSetTasks(array $tasks): void
    {
        foreach ($this->taskPool->getAll() as $taskId => $existingTask) {
            if (!$existingTask instanceof TaskSetInterface) {
                continue;
            }

            $this->taskPool->set($taskId, new Task(
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
                $subTask = $tasks[$subTask] ?? $this->taskPool->get($subTask);
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
                $subTask = $tasks[$subTask] ?? $this->taskPool->get($subTask);
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
        $tasks = $this->findAll();

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
}
