<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\SdkUpdateAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\SdkUpdateAction\TaskCreatedAction;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TestCreateActionTest extends Unit
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
        $tasksFromDirectories = ['task1' => 'task', 'task2' => 'task', 'task3' => 'task'];
        $this->taskManager
            ->expects($this->once())
            ->method('initialize')
            ->with(array_values($tasksFromDirectories));
        $taskCreatedAction = new TaskCreatedAction($this->taskManager);

        // Act
        $taskCreatedAction->apply($taskIds, $tasksFromDirectories, []);
    }

    /**
     * @return void
     */
    public function testFilter(): void
    {
        // Arrange
        $task1 = $this->createMock(TaskInterface::class);
        $task1->expects($this->once())
            ->method('getId')
            ->willReturn('task1');
        $task2 = $this->createMock(TaskInterface::class);
        $task2->expects($this->exactly(2))
            ->method('getId')
            ->willReturn('task2');
        $tasksFromDirectories = [$task1, $task2];
        $tasksFromDatabase = ['task1' => 'task'];
        $taskCreatedAction = new TaskCreatedAction($this->taskManager);

        // Act
        $taskIds = $taskCreatedAction->filter($tasksFromDirectories, $tasksFromDatabase);

        // Assert
        $this->assertSame(['task2'], $taskIds);
    }
}
