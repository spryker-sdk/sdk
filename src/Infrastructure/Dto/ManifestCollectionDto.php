<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto;

class ManifestCollectionDto
{
    /**
     * @var array
     */
    protected array $tasks = [];

    /**
     * @var array
     */
    protected array $taskSets = [];

    /**
     * @return mixed[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param mixed[] $tasks
     */
    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    /**
     * @param array $task
     *
     * @return void
     */
    public function addTask(array $task): void
    {
        $this->tasks[$task['id']] = $task;
    }

    /**
     * @return mixed[]
     */
    public function getTaskSets(): array
    {
        return $this->taskSets;
    }

    /**
     * @param mixed[] $taskSets
     */
    public function setTaskSets(array $taskSets): void
    {
        $this->taskSets = $taskSets;
    }

    /**
     * @param array $taskSet
     *
     * @return void
     */
    public function addTaskSet(array $taskSet): void
    {
        $this->taskSets[$taskSet['id']] = $taskSet;
    }
}
