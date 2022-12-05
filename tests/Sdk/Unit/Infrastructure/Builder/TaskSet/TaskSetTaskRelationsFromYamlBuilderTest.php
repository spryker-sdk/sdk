<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskSet;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsFromYamlBuilder;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskSet
 * @group TaskSetTaskRelationsFromYamlBuilderTest
 * Add your own group annotations below this line
 */
class TaskSetTaskRelationsFromYamlBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildFromYamlTaskSetShouldThrowExceptionWhenTaskSetNotInExistingTasks(): void
    {
        // Arrange
        $yamlTaskSet = ['id' => 'task-set:id', 'tasks' => []];
        $taskSetTaskRelationsFromYamlBuilder = new TaskSetTaskRelationsFromYamlBuilder();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationsFromYamlBuilder->buildFromYamlTaskSet($yamlTaskSet, []);
    }

    /**
     * @return void
     */
    public function testBuildFromYamlTaskSetShouldThrowExceptionWhenSubTaskNotInExistingTasks(): void
    {
        // Arrange
        $taskSetMock = $this->createTaskMock('task-set:id');
        $yamlTaskSet = ['id' => 'task-set:id', 'tasks' => [['id' => 'task:id']]];
        $taskSetTaskRelationsFromYamlBuilder = new TaskSetTaskRelationsFromYamlBuilder();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationsFromYamlBuilder->buildFromYamlTaskSet($yamlTaskSet, ['task-set:id' => $taskSetMock]);
    }

    /**
     * @return void
     */
    public function testBuildFromYamlTaskSetShouldReturnRelations(): void
    {
        // Arrange
        $taskSetMock = $this->createTaskMock('task-set:id');
        $taskMock = $this->createTaskMock('task:id');
        $yamlTaskSet = ['id' => 'task-set:id', 'tasks' => [['id' => 'task:id']]];
        $taskSetTaskRelationsFromYamlBuilder = new TaskSetTaskRelationsFromYamlBuilder();

        // Act
        $relations = $taskSetTaskRelationsFromYamlBuilder->buildFromYamlTaskSet($yamlTaskSet, ['task-set:id' => $taskSetMock, 'task:id' => $taskMock]);

        // Assert
        $this->assertSame($taskMock, $relations[0]->getSubTask(), 'Relation has sub-task');
        $this->assertSame($taskSetMock, $relations[0]->getTaskSet(), 'Relation has task set');
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
