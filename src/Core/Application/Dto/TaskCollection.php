<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto;

class TaskCollection
{
    /**
     * @var array<string, array>
     */
    protected array $tasks;

    /**
     * @var array<string, array>
     */
    protected array $taskSets;

    /**
     * @param array $tasks
     * @param array $taskSets
     */
    public function __construct(array $tasks = [], array $taskSets = [])
    {
        $this->tasks = $tasks;
        $this->taskSets = $taskSets;
    }

    /**
     * @return array
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @return array
     */
    public function getTaskSets(): array
    {
        return $this->taskSets;
    }

    /**
     * @param string $id
     * @param array $task
     *
     * @return $this
     */
    public function addTask(string $id, array $task)
    {
        $this->tasks[$id] = $task;

        return $this;
    }

    /**
     * @param string $id
     * @param array $taskSet
     *
     * @return $this
     */
    public function addTaskSet(string $id, array $taskSet)
    {
        $this->taskSets[$id] = $taskSet;

        return $this;
    }
}
