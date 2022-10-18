<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\SdkUpdateAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\SdkUpdateAction\TaskRemovedAction;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group SdkUpdateAction
 * @group TestRemoveActionTest
 * Add your own group annotations below this line
 */
class TestRemoveActionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface
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
        $task = $this->createMock(TaskInterface::class);
        $taskIds = ['task1', 'task2'];
        $tasksFromDirectories = ['task1' => $task, 'task2' => $task];
        $this->taskManager
            ->expects($this->exactly(2))
            ->method('remove')
            ->with($task);
        $taskRemovedAction = new TaskRemovedAction($this->taskManager);

        // Act
        $taskRemovedAction->apply($taskIds, [], $tasksFromDirectories);
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
        $tasksFromDirectories = ['task1' => $task1];
        $tasksFromDatabase = ['task1' => $task1, 'task2' => $task2];
        $taskRemovedAction = new TaskRemovedAction($this->taskManager);

        // Act
        $taskIds = $taskRemovedAction->filter($tasksFromDirectories, $tasksFromDatabase);

        // Assert
        $this->assertSame(['task2'], $taskIds);
    }
}
