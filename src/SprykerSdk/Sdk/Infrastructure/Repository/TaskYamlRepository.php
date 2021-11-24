<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SplFileInfo;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepository implements TaskRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param iterable<\SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected Finder $fileFinder,
        protected Yaml $yamlParser,
        protected iterable $existingTasks = [],
    ) {
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('task_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())){
            throw new MissingSettingException('task_dirs are not configured properly');
        }

        $tasks = [];

        //read task from path, parse and create Task, later use DB for querying
        foreach ($this->fileFinder->in($taskDirSetting->getValues())->name('*.yaml')->files() as $taskFile) {
            $task = $this->buildTask($taskFile);
            $tasks[$task->getId()] = $task;
        }

        foreach ($this->existingTasks as $existingTask) {
            $tasks[$existingTask->getId()] = $existingTask;
        }

        return $tasks;
    }

    /**
     * @param string $taskId
     *
     * @return TaskInterface|null
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
     * @param array $data
     * @return array
     */
    protected function buildPlaceholders(array $data): array
    {
        $placeholders = [];

        foreach ($data['placeholders'] as $placeholderData) {
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
     * @return array<Command>
     */
    protected function buildCommands(array $data): array
    {
        $commands = [];

        if ($data['type'] === 'local_cli') {
            $commands[] = new Command(
                $data['command'],
                $data['type'],
                true,
            );
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface[]
     */
    protected function buildLifecycleCommands(array $data): array
    {
        $commands = [];

        foreach ($data['commands'] as $command) {
            $commands[] = new Command(
                $command['command'],
                $command['type'],
                true
            );
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleInterface[]
     */
    protected function buildFiles(array $data): array
    {
        $files = [];

        foreach ($data['files'] as $file) {
            $files[] = new File(
                $file['path'],
                $file['content']
            );
        }

        return $files;
    }

    /**
     * @param array $data
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleInterface|null
     */
    protected function buildLifecycle(array $data): ?LifecycleInterface
    {
        if (!isset($data['lifecycle']['INITIALIZED'])) {
            return null;
        }

        $initializedEvent = new InitializedEvent(
            $this->buildLifecycleCommands($data['lifecycle']['INITIALIZED']),
            $this->buildPlaceholders($data['lifecycle']['INITIALIZED']),
            $this->buildFiles($data['lifecycle']['INITIALIZED'])
        );

        return new Lifecycle($initializedEvent);
    }

    /**
     * @param \SplFileInfo $taskFile
     *
     * @return TaskInterface
     */
    protected function buildTask(SplFileInfo $taskFile): TaskInterface
    {
        $data = $this->yamlParser->parse($taskFile->getContents());

        $placeholders = $this->buildPlaceholders($data);
        $commands = $this->buildCommands($data);
        $lifecycle = $this->buildLifecycle($data);

        return new Task(
            $data['id'],
            $data['short_description'],
            $commands,
            $placeholders,
            $data['help'] ?? null,
            $data['version'] ?? null,
            $data['successor'] ?? null,
            $data['deprecated'] ?? false,
            $lifecycle
        );
    }
}
