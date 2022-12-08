<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskSet;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsBuilder;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskSet
 * @group TaskSetTaskRelationsBuilderTest
 * Add your own group annotations below this line
 */
class TaskSetTaskRelationsBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildFromTaskSetShouldThrowExceptionWhenTaskSetNotInExistingTasks(): void
    {
        // Arrange
        $taskSetMock = $this->createTaskSetMock('task-set:id', []);
        $taskSetTaskRelationsBuilder = new TaskSetTaskRelationsBuilder();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationsBuilder->buildFromTaskSet($taskSetMock, []);
    }

    /**
     * @return void
     */
    public function testBuildFromTaskSetShouldThrowExceptionWhenSubTaskIdNotInExistingTasks(): void
    {
        // Arrange
        $taskSetMock = $this->createTaskSetMock('task-set:id', ['task:id']);
        $taskSetTaskRelationsBuilder = new TaskSetTaskRelationsBuilder();
        $existingTasks = ['task-set:id' => $taskSetMock];

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationsBuilder->buildFromTaskSet($taskSetMock, $existingTasks);
    }

    /**
     * @return void
     */
    public function testBuildFromTaskSetShouldSkipWhenSubTaskInstanceNotInExistingTasks(): void
    {
        // Arrange
        $taskSetMock = $this->createTaskSetMock('task-set:id', [$this->createTaskMock('task:id')]);
        $taskSetTaskRelationsBuilder = new TaskSetTaskRelationsBuilder();
        $existingTasks = ['task-set:id' => $taskSetMock];

        // Act
        $relations = $taskSetTaskRelationsBuilder->buildFromTaskSet($taskSetMock, $existingTasks);

        // Assert
        $this->assertEmpty($relations);
    }

    /**
     * @return void
     */
    public function testBuildFromTaskSetShouldCreateRelations(): void
    {
        // Arrange
        $taskMock = $this->createTaskMock('task:id');
        $taskMockOne = $this->createTaskMock('task:id:one');
        $taskSetMock = $this->createTaskSetMock('task-set:id', [$taskMock, 'task:id:one']);
        $taskSetTaskRelationsBuilder = new TaskSetTaskRelationsBuilder();
        $existingTasks = ['task-set:id' => $taskSetMock, 'task:id' => $taskMock, 'task:id:one' => $taskMockOne];

        // Act
        $relations = $taskSetTaskRelationsBuilder->buildFromTaskSet($taskSetMock, $existingTasks);

        // Assert
        $this->assertCount(2, $relations, 'Relations of 2 sub-tasks of task set');
        $this->assertSame($taskMock, $relations[0]->getSubTask(), 'Task object in task set');
        $this->assertSame($taskMockOne, $relations[1]->getSubTask(), 'Task id in tasks set');
    }

    /**
     * @param string $id
     * @param array<(\SprykerSdk\SdkContracts\Entity\TaskInterface|string)> $subTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSetMock(string $id, array $subTasks): TaskSetInterface
    {
        $taskSet = $this->createMock(TaskSetInterface::class);

        $taskSet->method('getId')->willReturn($id);
        $taskSet->method('getSubTasks')->willReturn($subTasks);

        return $taskSet;
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
