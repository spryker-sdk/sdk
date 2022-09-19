<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto;

class TaskCollection
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
     * @param array $tasks
     * @param array $taskSets
     */
    public function __construct(array $tasks, array $taskSets)
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
}
