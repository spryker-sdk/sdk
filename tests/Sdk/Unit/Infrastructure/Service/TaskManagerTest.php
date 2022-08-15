<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
use SprykerSdk\Sdk\Infrastructure\Service\TaskManager;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSetToTaskConverter;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskManagerTest extends Unit
{
    /**
     * @var string
     */
    protected const EXISTENT_TASK_ID = '1';

    /**
     * @var string
     */
    protected const NON_EXISTENT_TASK_ID = '2';

    /**
     * @return void
     */
    public function testSkipInitializeWhenTaskExists(): void
    {
        //Arrange
        $task = $this->createTaskMock(static::EXISTENT_TASK_ID);
        $taskManager = new TaskManager(
            $this->createNoCallsEventDispatcherMock(),
            $this->createRepositoryMock($task),
            $this->createTaskSetToTaskConverterMock(),
        );

        //Act
        $tasks = $taskManager->initialize([$task]);

        //Assert
        $this->assertCount(0, $tasks);
    }

    /**
     * @return void
     */
    public function testInitializeWhenTaskDoesNotExist(): void
    {
        //Arrange
        $task = $this->createTaskMock(static::NON_EXISTENT_TASK_ID);
        $taskManager = new TaskManager(
            $this->createEventDispatcherMock(InitializedEvent::class, InitializedEvent::NAME),
            $this->createRepositoryMock($task, 'create'),
            $this->createTaskSetToTaskConverterMock(),
        );

        //Act
        $tasks = $taskManager->initialize([$task]);

        //Assert
        $this->assertCount(1, $tasks);
    }

    /**
     * @return void
     */
    public function testInitializeTaskSetWhenTaskSetExists(): void
    {
        //Arrange
        $taskSet = $this->createTaskSetMock();
        $task = $this->createTaskMock(static::EXISTENT_TASK_ID);
        $taskManager = new TaskManager(
            $this->createEventDispatcherMock(InitializedEvent::class, InitializedEvent::NAME),
            $this->createRepositoryMock($task, 'create'),
            $this->createTaskSetToTaskConverterMock($task),
        );

        //Act
        $tasks = $taskManager->initialize([$taskSet]);

        //Assert
        $this->assertCount(1, $tasks);
    }

    /**
     * @return void
     */
    public function testTriggerEventWithRepositoryCallWhenTaskRemoved(): void
    {
        //Arrange
        $task = $this->createTaskMock(static::EXISTENT_TASK_ID);
        $taskManager = new TaskManager(
            $this->createEventDispatcherMock(RemovedEvent::class, RemovedEvent::NAME),
            $this->createRepositoryMock($task, 'remove'),
            $this->createTaskSetToTaskConverterMock(),
        );

        //Act
        $taskManager->remove($task);
    }

    /**
     * @return void
     */
    public function testTriggerEventWithRepositoryCallWhenTaskUpdated(): void
    {
        //Arrange
        $task = $this->createTaskMock(static::EXISTENT_TASK_ID);
        $taskManager = new TaskManager(
            $this->createEventDispatcherMock(UpdatedEvent::class, UpdatedEvent::NAME),
            $this->createRepositoryMock($task, 'update'),
            $this->createTaskSetToTaskConverterMock(),
        );

        //Act
        $taskManager->update($task, $task);
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId): TaskInterface
    {
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->method('getId')->willReturn($taskId);

        return $taskMock;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSetMock(): TaskInterface
    {
        return $this->createMock(TaskSetInterface::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param string|null $expectedMethodName
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository
     */
    protected function createRepositoryMock(TaskInterface $task, ?string $expectedMethodName = null): TaskRepository
    {
        $repositoryMock = $this->createMock(TaskRepository::class);
        $repositoryMock->method('findById')->willReturnMap([[static::EXISTENT_TASK_ID, $task], [static::NON_EXISTENT_TASK_ID, null]]);

        if ($expectedMethodName === null) {
            return $repositoryMock;
        }

        $repositoryMock
            ->expects($this->once())
            ->method($expectedMethodName)
            ->with($this->equalTo($task));

        return $repositoryMock;
    }

    /**
     * @param string $eventClass
     * @param string $eventName
     *
     * @return \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
     */
    protected function createEventDispatcherMock(string $eventClass, string $eventName): EventDispatcherInterface
    {
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf($eventClass), $this->equalTo($eventName));

        return $eventDispatcherMock;
    }

    /**
     * @return \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
     */
    protected function createNoCallsEventDispatcherMock(): EventDispatcherInterface
    {
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherMock
            ->expects($this->never())
            ->method('dispatch');

        return $eventDispatcherMock;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface|null $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\TaskSetToTaskConverter
     */
    protected function createTaskSetToTaskConverterMock(?TaskInterface $task = null): TaskSetToTaskConverter
    {
        $taskSetToTaskConverterMock = $this->createMock(TaskSetToTaskConverter::class);
        if ($task !== null) {
            $taskSetToTaskConverterMock->expects($this->once())->method('convert')->willReturn($task);
        }

        return $taskSetToTaskConverterMock;
    }
}
