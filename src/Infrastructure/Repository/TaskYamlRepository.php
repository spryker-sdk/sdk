<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidConfigurationException;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskSetManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
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
     * @var array<string, array>
     */
    protected $taskListData = [];

    /**
     * @var array<string, array>
     */
    protected $taskSetsData = [];

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
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $existingTasks = [];

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    protected ManifestValidatorInterface $manifestValidation;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface $manifestValidation
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder,
        ManifestValidatorInterface $manifestValidation,
        iterable $existingTasks = []
    ) {
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
        $this->manifestValidation = $manifestValidation;
        foreach ($existingTasks as $existingTask) {
            $this->existingTasks[$existingTask->getId()] = $existingTask;
        }
    }

    /**
     * @param array $tags
     *
     * @return array
     */
    public function findAll(array $tags = []): array
    {
        if (!$this->taskListData || !$this->taskSetsData) {
            $this->readTaskYaml();
        }

        $this->taskListData = $this->manifestValidation->validate(TaskManifestConfiguration::NAME, $this->taskListData);
        $this->taskSetsData = $this->manifestValidation->validate(TaskSetManifestConfiguration::NAME, $this->taskSetsData);

        $tasks = [];
        foreach ($this->taskListData as $taskData) {
            $task = $this->buildTask($taskData, $this->taskListData, $tags);
            $tasks[$task->getId()] = $task;
        }

        $existingTasks = array_merge($this->existingTasks, $tasks);

        foreach ($this->taskSetsData as $taskData) {
            $task = $this->buildTaskSet($taskData, $this->taskListData, $existingTasks, $tags);
            $tasks[$task->getId()] = $task;
        }

        return array_merge($tasks, $this->existingTasks);
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
        $this->readTaskYaml();

        return isset($this->taskListData[$taskId]) ||
            isset($this->existingTasks[$taskId]) ||
            (isset($this->taskSetsData[$taskId]) && $includeTaskSet);
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
        $this->readTaskYaml();
        $taskPlaceholders = [];
        foreach ($taskIds as $taskId) {
            if (isset($this->taskListData[$taskId])) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (array $placeholder) {
                        return $placeholder['name'];
                    },
                    $this->taskListData[$taskId]['placeholders'],
                );

                continue;
            }

            if (isset($this->existingTasks[$taskId])) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (PlaceholderInterface $placeholder) {
                        return $placeholder->getName();
                    },
                    $this->existingTasks[$taskId]->getPlaceholders(),
                );

                continue;
            }

            if ($includeTaskSet && isset($this->taskSetsData[$taskId])) {
                $taskPlaceholders[$taskId] = array_map(
                    static function (array $placeholder) {
                        return $placeholder['name'];
                    },
                    $this->taskSetsData[$taskId]['placeholders'],
                );
            }
        }

        return $taskPlaceholders;
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
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidConfigurationException
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return void
     */
    protected function readTaskYaml(): void
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('extension_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('extension_dirs are not configured properly');
        }

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        //read task from path, parse and create Task, later use DB for querying
        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());

            if (!isset($taskData['id'])) {
                throw new InvalidConfigurationException(sprintf('Invalid configuration for path "%s, `id` doesn\'t exist.": ', $taskFile->getFilename()));
            }

            if (!isset($taskData['type'])) {
                throw new InvalidConfigurationException(sprintf('Invalid configuration for path "%s, `type` doesn\'t exist.": ', $taskFile->getFilename()));
            }

            if ($taskData['type'] === static::TASK_SET_TYPE) {
                $this->taskSetsData[(string)$taskData['id']] = $taskData;
            } else {
                $this->taskListData[(string)$taskData['id']] = $taskData;
            }
        }
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
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException
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
                    throw new TaskSetNestingException('Task set can\'t have another task set inside.');
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
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\FileInterface>
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
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface
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
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function buildTaskSet(array $taskData, array $taskListData, array $tasks, array $tags = []): TaskInterface
    {
        return $this->taskFromYamlTaskSetBuilder->buildTaskFromYamlTaskSet($taskData, $taskListData, $tasks);
    }

    /**
     * @param string $taskId
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getExistingTask(string $taskId): TaskInterface
    {
        if ($this->existingTasks[$taskId] instanceof TaskSetInterface) {
            throw new TaskSetNestingException(sprintf(
                'Task set with id %s can\'t have another task set inside.',
                $taskId,
            ));
        }

        return $this->existingTasks[$taskId];
    }
}
