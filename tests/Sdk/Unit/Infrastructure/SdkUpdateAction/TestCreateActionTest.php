<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\SdkUpdateAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use SprykerSdk\Sdk\Infrastructure\SdkUpdateAction\TaskCreatedAction;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TestCreateActionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

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
        $tasksFromDirectories = [];
        foreach (['task1' => 'task', 'task2' => 'task', 'task3' => 'task'] as $taskId => $task) {
            $tasksFromDirectories[$taskId] = $this->tester->createTask(['id' => $taskId]);
        }

        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI);
        $criteriaDto->setTaskCollection($tasksFromDirectories);
        $this->taskManager
            ->expects($this->once())
            ->method('initialize')
            ->with($criteriaDto);
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
        $tasksFromDatabase = [];
        foreach (['task1' => 'task'] as $taskId => $task) {
            $tasksFromDatabase[$taskId] = $this->tester->createTask(['id' => $taskId]);
        }
        $taskCreatedAction = new TaskCreatedAction($this->taskManager);

        // Act
        $taskIds = $taskCreatedAction->filter($tasksFromDirectories, $tasksFromDatabase);

        // Assert
        $this->assertSame(['task2'], $taskIds);
    }
}
