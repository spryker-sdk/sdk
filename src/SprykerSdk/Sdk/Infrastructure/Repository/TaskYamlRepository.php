<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Finder\Finder;

class TaskYamlRepository implements TaskRepositoryInterface
{
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected Finder $fileFinder
    ) {
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $taskDirSetting = $this->settingRepository->findByPath('task_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->value)){
            //@todo handle error
        }

        $tasks = [];

        //read task from path, parse and create Task
        //later use DB for querying
        foreach ($this->fileFinder->in($taskDirSetting->value)->name('*.yml')->files() as $taskFile) {
            //@todo parse task file
            $task = new Task(
                'fake_id',
                'fake_description',
                [],
                [],
            );

            $tasks[$task->id] = $task;
        }

        return $tasks;
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task|null
     */
    public function findById(string $taskId): ?Task
    {
        $tasks = $this->findAll();

        if (array_key_exists($taskId, $tasks)) {
            return $tasks[$taskId];
        }

        return null;
    }

}