<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
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
     * @return array<mixed>
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param array<mixed> $tasks
     *
     * @return void
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
     * @return array<mixed>
     */
    public function getTaskSets(): array
    {
        return $this->taskSets;
    }

    /**
     * @param array<mixed> $taskSets
     *
     * @return void
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
