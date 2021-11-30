<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepository implements TaskRepositoryInterface
{
    protected SettingRepositoryInterface $settingRepository;

    protected Finder $fileFinder;

    protected Yaml $yamlParser;

    protected iterable $existingTasks = [];

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param iterable<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        iterable $existingTasks = []
    ) {
        $this->existingTasks = $existingTasks;
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param array $tags
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return array
     */
    public function findAll(array $tags = []): array
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('task_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('task_dirs are not configured properly');
        }

        $tasks = [];
        $taskListData = [];
        //read task from path, parse and create Task, later use DB for querying
        foreach ($this->fileFinder->in($taskDirSetting->getValues())->name('*.yaml')->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());
            $taskListData[$taskData['id']] = $taskData;
        }

        foreach ($taskListData as $taskData) {
            $task = $this->buildTask($taskData, $taskListData, $tags);
            $tasks[$task->getId()] = $task;
        }

        foreach ($this->existingTasks as $existingTask) {
            $tasks[$existingTask->getId()] = $existingTask;
        }

        return $tasks;
    }

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface|null
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
     * @param array $data
     * @param array $taskListData
     * @param array $tags
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected function buildPlaceholders(array $data, array $taskListData, array $tags = []): array
    {
        $placeholders = [];
        $taskPlaceholders = [];
        $taskPlaceholders[] = $data['placeholders'] ?? [];

        if (isset($data['type']) === 'task_set') {
            foreach ($data['tasks'] as $task) {
                if ($tags && !array_intersect($tags, $task['tags'])) {
                    continue;
                }
                $taskPlaceholders[] = $taskListData[$task['id']]['placeholders'];
            }
        }
        $taskPlaceholders = array_merge(...$taskPlaceholders);

        foreach ($taskPlaceholders as $placeholderData) {
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
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Command>
     */
    protected function buildCommands(array $data, array $taskListData, array $tags = []): array
    {
        $commands = [];

        if ($data['type'] === 'local_cli') {
            $commands[] = new Command(
                $data['command'],
                $data['type'],
                true,
            );
        }

        if ($data['type'] === 'task_set') {
            foreach ($data['tasks'] as $task) {
                if ($tags && !array_intersect($tags, $task['tags'])) {
                    continue;
                }
                $commands[] = new Command(
                    $taskListData[$task['id']]['command'],
                    $taskListData[$task['id']]['type'],
                    $task['stop_on_error'],
                    $task['tags'],
                );
            }
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
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
                true,
            );
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface>
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
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface|null
     */
    protected function buildLifecycle(array $taskData, array $taskListData, array $tags = []): ?LifecycleInterface
    {
        return new Lifecycle(
            $this->buildInitializedEvent($taskData, $taskListData, $tags),
            $this->buildUpdatedEvent($taskData, $taskListData, $tags),
            $this->buildRemovedEvent($taskData, $taskListData, $tags),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent
     */
    protected function buildInitializedEvent(array $taskData, array $taskListData, array $tags = []): InitializedEvent
    {
        $event = new InitializedEvent();

        if (isset($taskData['lifecycle']['INITIALIZED'])) {
            $eventData = $taskData['lifecycle']['INITIALIZED'];

            $event
                ->setCommands($this->buildLifecycleCommands($eventData))
                ->setPlaceholders($this->buildPlaceholders($eventData, $taskListData, $tags))
                ->setFiles($this->buildFiles($eventData));
        }

        return $event;
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent
     */
    protected function buildRemovedEvent(array $taskData, array $taskListData, array $tags = []): RemovedEvent
    {
        $event = new RemovedEvent();

        if (isset($taskData['lifecycle']['REMOVED'])) {
            $eventData = $taskData['lifecycle']['REMOVED'];

            $event
                ->setCommands($this->buildLifecycleCommands($eventData))
                ->setPlaceholders($this->buildPlaceholders($eventData, $taskListData, $tags))
                ->setFiles($this->buildFiles($eventData));
        }

        return $event;
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent
     */
    protected function buildUpdatedEvent(array $taskData, array $taskListData, array $tags = []): UpdatedEvent
    {
        $event = new UpdatedEvent();

        if (isset($taskData['lifecycle']['UPDATED'])) {
            $eventData = $taskData['lifecycle']['UPDATED'];

            $event
                ->setCommands($this->buildLifecycleCommands($eventData))
                ->setPlaceholders($this->buildPlaceholders($eventData, $taskListData, $tags))
                ->setFiles($this->buildFiles($eventData));
        }

        return $event;
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    protected function buildTask(array $taskData, array $taskListData, array $tags = []): TaskInterface
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
        );
    }
}
