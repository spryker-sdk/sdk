<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\SdkUpdateAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
use SprykerSdk\Sdk\Infrastructure\SdkUpdateAction\TaskDeprecatedAction;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TestDeprecateActionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository
     */
    protected TaskRepository $taskRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->taskManager = $this->createMock(TaskManagerInterface::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testApply(): void
    {
        // Arrange
        $task1 = $this->createMock(TaskInterface::class);
        $task1->expects($this->once())
            ->method('getSuccessor')
            ->willReturn(null);
        $task2 = $this->createMock(TaskInterface::class);
        $task2->expects($this->exactly(2))
            ->method('getSuccessor')
            ->willReturn('successor2');
        $task3 = $this->createMock(TaskInterface::class);
        $task3->expects($this->exactly(3))
            ->method('getSuccessor')
            ->willReturn('successor3');
        $tasksFromDirectories = $tasksFromDatabase = ['successor1' => $task1, 'successor2' => $task2, 'successor3' => $task3];
        $this->taskRepository
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturn(true, false);
        $this->taskManager
            ->expects($this->once())
            ->method('initialize');
        $taskDeprecatedAction = new TaskDeprecatedAction($this->taskRepository, $this->taskManager);

        // Act
        $taskDeprecatedAction->apply(array_keys($tasksFromDirectories), $tasksFromDirectories, $tasksFromDatabase);
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
        $task2->expects($this->once())
            ->method('isDeprecated')
            ->willReturn(true);
        $task3 = $this->createMock(TaskInterface::class);
        $task3->expects($this->once())
            ->method('getId')
            ->willReturn('task3');
        $tasksFromDirectories = ['task1' => $task1, 'task2' => $task2];
        $tasksFromDatabase = ['task1' => $task1, 'task2' => $task2, 'task3' => $task3];

        $taskDeprecatedAction = new TaskDeprecatedAction($this->taskRepository, $this->taskManager);

        // Act
        $taskIds = $taskDeprecatedAction->filter($tasksFromDirectories, $tasksFromDatabase);

        $this->assertCount(1, $taskIds);
        $this->assertSame('task2', current($taskIds));
    }
}
