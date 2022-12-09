<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet;

use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetTaskRelationsBuilder implements TaskSetTaskRelationsBuilderInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @throws \InvalidArgumentException
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function buildFromTaskSet(TaskSetInterface $taskSet, array $existingTasks): array
    {
        if (!isset($existingTasks[$taskSet->getId()])) {
            throw new InvalidArgumentException(sprintf('Can\'t find loaded task set `%s`', $taskSet->getId()));
        }

        $relations = [];

        foreach ($taskSet->getSubTasks() as $task) {
            $subTask = is_string($task)
                ? $this->getSubTaskByTaskId($task, $existingTasks)
                : $this->getSubTaskByTask($task, $existingTasks);

            if ($subTask === null) {
                continue;
            }

            $relations[] = new TaskSetTaskRelation($existingTasks[$taskSet->getId()], $subTask);
        }

        return $relations;
    }

    /**
     * @param string $taskId
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getSubTaskByTaskId(string $taskId, array $existingTasks): TaskInterface
    {
        if (!isset($existingTasks[$taskId])) {
            throw new InvalidArgumentException(sprintf('Task %s not found', $taskId));
        }

        return $existingTasks[$taskId];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    protected function getSubTaskByTask(TaskInterface $task, array $existingTasks): ?TaskInterface
    {
        return $existingTasks[$task->getId()] ?? null;
    }
}
