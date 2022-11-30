<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Storage;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskSetTaskRelationStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * @group Unit
 * @group Infrastructure
 * @group Storage
 * @group TaskSetTaskRelationStorageTest
 */
class TaskSetTaskRelationStorageTest extends Unit
{
    /**
     * @return void
     */
    public function testAddTaskSetTasRelationsShouldAddRelations(): void
    {
        // Arrange
        $storage = new TaskSetTaskRelationStorage();
        $relation = new TaskSetTaskRelation(
            $this->createTaskMock('task-set:id'),
            $this->createTaskMock('task:id'),
        );

        $relationOne = new TaskSetTaskRelation(
            $this->createTaskMock('task-set:id'),
            $this->createTaskMock('task:id:one'),
        );

        // Act
        $storage->addTaskSetTasRelations([$relation, $relationOne]);
        $relations = $storage->getTaskSetTaskRelations('task-set:id');

        // Assert
        $this->assertSame([$relation, $relationOne], $relations);
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId): TaskInterface
    {
        $task = $this->createMock(TaskInterface::class);
        $task->method('getId')->willReturn($taskId);

        return $task;
    }
}
