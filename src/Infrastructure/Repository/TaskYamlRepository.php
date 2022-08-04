<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepository implements TaskYamlRepositoryInterface
{
    /**
     * @var string
     */
    protected const TASK_SET_TYPE = 'task_set';

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
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $existingTasks = [];

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        iterable $existingTasks = []
    ) {
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
        foreach ($existingTasks as $existingTask) {
            $this->existingTasks[$existingTask->getId()] = $existingTask;
        }
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

        $existedDirectories = $this->findExistedDirectories($taskDirSetting->getValues());

        $finder = $this->fileFinder
            ->in(array_map(fn (string $directory): string => $directory . '/*/Task/', $existedDirectories))
            ->name('*.yaml');

        //read task from path, parse and create Task, later use DB for querying
        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());

            if ($taskData['type'] === static::TASK_SET_TYPE) {
                $taskSetsData[$taskData['id']] = $taskData;
            } else {
                $taskListData[$taskData['id']] = $taskData;
            }
        }

        foreach ($taskListData as $taskData) {
            $task = $this->buildTask($taskData, $taskListData, $tags);
            $tasks[$task->getId()] = $task;
        }

        foreach ($taskSetsData as $taskData) {
            $task = $this->buildTaskSet($taskData, $taskListData, $tasks, $tags);
            $tasks[$task->getId()] = $task;
        }

        $this->extractTaskSetTasks($tasks);

        return array_merge($tasks, $this->existingTasks);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return void
     */
    protected function extractTaskSetTasks(array $tasks): void
    {
        foreach ($this->existingTasks as $taskId => $existingTask) {
            if (!($existingTask instanceof TaskSetInterface)) {
                continue;
            }

            $commands = [];
            $placeholders = [];

            foreach ($existingTask->getSubTasks() as $subTask) {
                if (is_string($subTask)) {
                    $subTask = $tasks[$subTask] ?? $this->existingTasks[$subTask];
                }

                $commands[] = $this->extractExistingCommands($subTask);
                $placeholders[] = $subTask->getPlaceholders();
            }

            $commands = array_merge(...$commands);
            $placeholders = array_merge(...$placeholders);

            $this->existingTasks[$taskId] = new Task(
                $existingTask->getId(),
                $existingTask->getShortDescription(),
                $commands,
                $existingTask->getLifecycle(),
                $existingTask->getVersion(),
                $placeholders,
                $existingTask->getHelp(),
                $existingTask->getSuccessor(),
                $existingTask->isDeprecated(),
                ContextInterface::DEFAULT_STAGE,
                $existingTask->isOptional(),
                $existingTask->getStages(),
            );
        }
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
        return array_filter($directorySettings, function (string $dir): bool {
            $found = glob($dir . '/*/Task');

            if ($found === false) {
                return false;
            }

            return count($found) > 0;
        });
    }

    /**
     * @param array $data
     * @param array $taskListData
     * @param array $tags
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function buildPlaceholders(array $data, array $taskListData, array $tags = []): array
    {
        $placeholders = [];
        $taskPlaceholders = [];
        $taskPlaceholders[] = $data['placeholders'] ?? [];

        if (isset($data['type']) && $data['type'] === static::TASK_SET_TYPE) {
            foreach ($data['tasks'] as $task) {
                $taskTags = $task['tags'] ?? [];
                if ($tags && !array_intersect($tags, $taskTags)) {
                    continue;
                }

                $taskPlaceholders[] = isset($taskListData[$task['id']]) ?
                    $taskListData[$task['id']]['placeholders'] :
                    $this->getExistingTask($task['id'])->getPlaceholders();
            }
        }
        $taskPlaceholders = array_merge(...$taskPlaceholders);

        foreach ($taskPlaceholders as $placeholderData) {
            if ($placeholderData instanceof PlaceholderInterface) {
                $placeholders[$placeholderData->getName()] = $placeholderData;

                continue;
            }

            $placeholderName = $placeholderData['name'];
            $placeholders[$placeholderName] = new Placeholder(
                $placeholderName,
                $placeholderData['value_resolver'],
                $placeholderData['configuration'] ?? [],
                $placeholderData['optional'] ?? false,
            );
        }

        return $placeholders;
    }

    /**
     * @param array $data
     * @param array $taskListData
     * @param array<string> $tags
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException
     *
     * @return array<int, \SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildCommands(array $data, array $taskListData, array $tags = []): array
    {
        $commands = [];

        if (in_array($data['type'], ['local_cli', 'local_cli_interactive'], true)) {
            $converter = isset($data['report_converter']) ? new Converter(
                $data['report_converter']['name'],
                $data['report_converter']['configuration'],
            ) : null;
            $commands[] = new Command(
                $data['command'],
                $data['type'],
                false,
                $data['tags'] ?? [],
                $converter,
                $data['stage'] ?? ContextInterface::DEFAULT_STAGE,
                $data['error_message'] ?? '',
            );
        }

        if ($data['type'] === static::TASK_SET_TYPE) {
            foreach ($data['tasks'] as $task) {
                $tasksTags = $task['tags'] ?? [];
                if ($tags && !array_intersect($tags, $tasksTags)) {
                    continue;
                }
                $taskData = $taskListData[$task['id']] ?? $this->getExistingTask($task['id']);

                if ($taskData instanceof TaskSetInterface) {
                    throw new TaskMissingException('Task set can\'t have another task set inside.');
                }

                if ($taskData instanceof TaskInterface) {
                    foreach ($taskData->getCommands() as $command) {
                        $commands[] = $command;
                    }

                    continue;
                }

                $converter = isset($taskData['report_converter']) ? new Converter(
                    $taskData['report_converter']['name'],
                    $taskData['report_converter']['configuration'],
                ) : null;

                $commands[] = new Command(
                    $taskData['command'],
                    $taskData['type'],
                    $task['stop_on_error'],
                    $tasksTags,
                    $converter,
                    $taskData['stage'] ?? ContextInterface::DEFAULT_STAGE,
                    $data['error_message'] ?? '',
                );
            }
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildLifecycleCommands(array $data): array
    {
        $commands = [];

        if (!isset($data['commands'])) {
            return $commands;
        }

        foreach ($data['commands'] as $command) {
            $commands[] = new Command(
                $command['command'],
                $command['type'],
                false,
            );
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    protected function buildFiles(array $data): array
    {
        $files = [];

        if (!isset($data['files'])) {
            return $files;
        }

        foreach ($data['files'] as $file) {
            $files[] = new File(
                $file['path'],
                $file['content'],
            );
        }

        return $files;
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    protected function buildLifecycle(array $taskData, array $taskListData, array $tags = []): TaskLifecycleInterface
    {
        return new Lifecycle(
            $this->buildInitializedEventData($taskData, $taskListData, $tags),
            $this->buildUpdatedEventData($taskData, $taskListData, $tags),
            $this->buildRemovedEventData($taskData, $taskListData, $tags),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    protected function buildInitializedEventData(array $taskData, array $taskListData, array $tags = []): InitializedEventData
    {
        if (!isset($taskData['lifecycle']['INITIALIZED'])) {
            return new InitializedEventData();
        }

        $eventData = $taskData['lifecycle']['INITIALIZED'];

        return new InitializedEventData(
            $this->buildLifecycleCommands($eventData),
            $this->buildPlaceholders($eventData, $taskListData, $tags),
            $this->buildFiles($eventData),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    protected function buildRemovedEventData(array $taskData, array $taskListData, array $tags = []): RemovedEventData
    {
        if (!isset($taskData['lifecycle']['REMOVED'])) {
            return new RemovedEventData();
        }

        $eventData = $taskData['lifecycle']['REMOVED'];

        return new RemovedEventData(
            $this->buildLifecycleCommands($eventData),
            $this->buildPlaceholders($eventData, $taskListData, $tags),
            $this->buildFiles($eventData),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    protected function buildUpdatedEventData(array $taskData, array $taskListData, array $tags = []): UpdatedEventData
    {
        if (!isset($taskData['lifecycle']['UPDATED'])) {
            return new UpdatedEventData();
        }

        $eventData = $taskData['lifecycle']['UPDATED'];

        return new UpdatedEventData(
            $this->buildLifecycleCommands($eventData),
            $this->buildPlaceholders($eventData, $taskListData, $tags),
            $this->buildFiles($eventData),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    protected function buildTask(array $taskData, array $taskListData, array $tags = []): Task
    {
        $placeholders = $this->buildPlaceholders($taskData, $taskListData, $tags);
        $commands = $this->buildCommands($taskData, $taskListData, $tags);
        $lifecycle = $this->buildLifecycle($taskData, $taskListData, $tags);

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
     * @param array $taskData
     * @param array $taskListData
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    protected function buildTaskSet(array $taskData, array $taskListData, array $tasks, array $tags = []): Task
    {
        $task = $this->buildTask($taskData, $taskListData, $tags);

        if (!isset($taskData['tasks'])) {
            return $task;
        }

        $taskSetPlaceholders = [];

        foreach ($taskData['tasks'] as $subTaskData) {
            $subTask = $tasks[$subTaskData['id']] ?? null;

            if ($subTask === null) {
                continue;
            }

            $taskSetPlaceholders[] = $subTask->getPlaceholders();
        }

        $task->setPlaceholdersArray(array_merge(...$taskSetPlaceholders));

        return $task;
    }

    /**
     * @param string $taskId
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getExistingTask(string $taskId): TaskInterface
    {
        if ($this->existingTasks[$taskId] instanceof TaskSetInterface) {
            throw new TaskMissingException('Task set can\'t have another task set inside.');
        }

        return $this->existingTasks[$taskId];
    }
}
