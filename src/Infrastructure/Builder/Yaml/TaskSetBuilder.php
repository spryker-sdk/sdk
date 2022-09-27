<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskSetBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder
     */
    protected TaskBuilder $taskBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder $taskBuilder
     */
    public function __construct(TaskBuilder $taskBuilder)
    {
        $this->taskBuilder = $taskBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTask(TaskYaml $taskYaml): Task
    {
        $task = $this->taskBuilder->buildTaskByTaskYaml($taskYaml);
        $taskData = $taskYaml->getTaskData();

        if (!isset($taskData['tasks'])) {
            return $task;
        }

        $taskSetPlaceholders = [];
        $tasks = $taskYaml->getTasks();

        foreach ($taskData['tasks'] as $subTaskData) {
            $subTask = $tasks[$subTaskData['id']] ?? null;

            if ($subTask instanceof TaskInterface) {
                $taskSetPlaceholders[] = $subTask->getPlaceholders();
            }
        }

        $task->setPlaceholdersArray(array_merge(...$taskSetPlaceholders));

        return $task;
    }
}
