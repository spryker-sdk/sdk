<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTaskSet(TaskYaml $taskYaml): Task
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

            if ($subTask instanceof TaskInterface) {
                $taskSetPlaceholders[] = $subTask->getPlaceholders();
            }
        }

        $task->setPlaceholdersArray(array_merge(...$taskSetPlaceholders));

        return $task;
    }
}
