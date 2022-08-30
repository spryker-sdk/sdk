<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Task;

class TaskSetBuilder implements TaskSetBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilderInterface $taskBuilder
     */
    public function __construct(TaskBuilderInterface $taskBuilder)
    {
        $this->taskBuilder = $taskBuilder;
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTaskSet(array $taskData, array $taskListData, array $tasks, array $tags = []): Task
    {
        $task = $this->taskBuilder->buildTask($taskData, $taskListData, $tags);

        if (!isset($taskData['tasks'])) {
            return $task;
        }

        $taskSetPlaceholders = [];

        foreach ($taskData['tasks'] as $subTaskData) {
            $subTask = $tasks[$subTaskData['id']] ?? null;

            if ($subTask === null) {
                continue;
            }

            $taskSetPlaceholders[] = $subTask->getPlaceholders();
        }

        $task->setPlaceholdersArray(array_merge(...$taskSetPlaceholders));

        return $task;
    }
}
