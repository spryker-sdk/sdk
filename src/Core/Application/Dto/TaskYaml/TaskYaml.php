<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\TaskYaml;

class TaskYaml implements TaskYamlInterface
{
    /**
     * @var array
     */
    protected array $taskData = [];

    /**
     * @var array
     */
    protected array $taskListData = [];

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $tasks = [];

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     */
    public function __construct(array $taskData, array $taskListData, array $tasks = [])
    {
        $this->taskData = $taskData;
        $this->taskListData = $taskListData;
        $this->tasks = $tasks;
    }

    /**
     * @return array
     */
    public function getTaskData(): array
    {
        return $this->taskData;
    }

    /**
     * @return array
     */
    public function getTaskListData(): array
    {
        return $this->taskListData;
    }

    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param array<string, mixed> $taskData
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface
     */
    public function withTaskData(array $taskData)
    {
        $that = clone $this;
        $that->setTaskData($taskData);

        return $that;
    }

    /**
     * @param array<string, mixed> $taskData
     *
     * @return $this
     */
    public function setTaskData(array $taskData)
    {
        $this->taskData = $taskData;

        return $this;
    }
}
