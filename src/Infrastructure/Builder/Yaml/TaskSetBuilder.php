<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTaskSet(TaskYamlInterface $taskYaml): Task
    {
        $task = $this->taskBuilder->buildTask($taskYaml);
        $taskData = $taskYaml->getTaskData();

        if (!isset($taskData['tasks'])) {
            return $task;
        }

        $taskSetPlaceholders = [];
        $tasks = $taskYaml->getTasks();

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
