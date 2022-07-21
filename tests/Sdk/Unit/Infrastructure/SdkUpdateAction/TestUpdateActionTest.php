<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\SdkUpdateAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\SdkUpdateAction\TaskUpdatedAction;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TestUpdateActionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->taskManager = $this->createMock(TaskManagerInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testApply(): void
    {
        // Arrange
        $taskIds = ['task1', 'task2', 'task3'];
        $task = $this->createMock(TaskInterface::class);
        $tasksFromDirectories = $tasksFromDatabase = ['task1' => $task, 'task2' => $task, 'task3' => $task];
        $this->taskManager
            ->expects($this->exactly(count($taskIds)))
            ->method('update')
            ->with($task, $task);
        $taskUpdatedAction = new TaskUpdatedAction($this->taskManager);

        // Act
        $taskUpdatedAction->apply($taskIds, $tasksFromDirectories, $tasksFromDatabase);
    }

    /**
     * @return void
     */
    public function testFilter(): void
    {
        // Arrange
        $task1 = $this->createMock(TaskInterface::class);
        $task1->expects($this->once())
            ->method('getVersion')
            ->willReturn('1.0.0');
        $task2 = $this->createMock(TaskInterface::class);
        $task2->expects($this->once())
            ->method('getVersion')
            ->willReturn('1.0.0');
        $task4 = $this->createMock(TaskInterface::class);
        $task4->expects($this->exactly(2))
            ->method('getId')
            ->willReturn('task1');
        $task4->expects($this->once())
            ->method('getVersion')
            ->willReturn('1.1.0');
        $task3 = $this->createMock(TaskInterface::class);
        $task3->expects($this->once())
            ->method('getId')
            ->willReturn('task2');
        $task3->expects($this->once())
            ->method('getVersion')
            ->willReturn('1.0.0');
        $tasksFromDirectories = [$task3, $task4];
        $tasksFromDatabase = ['task1' => $task1, 'task2' => $task2];
        $taskUpdatedAction = new TaskUpdatedAction($this->taskManager);

        // Act
        $taskIds = $taskUpdatedAction->filter($tasksFromDirectories, $tasksFromDatabase);

        // Assert
        $this->assertCount(1, $taskIds);
        $this->assertSame('task1', current($taskIds));
    }
}
