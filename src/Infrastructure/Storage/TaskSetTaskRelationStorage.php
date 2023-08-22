<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Storage;

class TaskSetTaskRelationStorage
{
    /**
     * @var array<string, array<string, \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>>
     */
    protected array $taskSetTaskRelations = [];

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface> $taskSetTaskRelations
     *
     * @return void
     */
    public function addTaskSetTasRelations(array $taskSetTaskRelations): void
    {
        foreach ($taskSetTaskRelations as $taskSetTaskRelation) {
            $taskSetId = $taskSetTaskRelation->getTaskSet()->getId();
            $subTaskId = $taskSetTaskRelation->getSubTask()->getId();

            $this->taskSetTaskRelations[$taskSetId][$subTaskId] = $taskSetTaskRelation;
        }
    }

    /**
     * @param string $taskSetId
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function getTaskSetTaskRelations(string $taskSetId): array
    {
        return isset($this->taskSetTaskRelations[$taskSetId])
            ? array_values($this->taskSetTaskRelations[$taskSetId])
            : [];
    }
}
