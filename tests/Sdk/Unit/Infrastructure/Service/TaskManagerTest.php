<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
use SprykerSdk\Sdk\Infrastructure\Service\TaskManager;
use SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation\TaskSetTaskRelationFacadeInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group TaskManagerTest
 * Add your own group annotations below this line
 */
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
            $this->createTaskFromTaskSetBuilderMock(),
            $this->createTaskSetTaskRelationFacadeMock(),
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
            $this->createTaskFromTaskSetBuilderMock(),
            $this->createTaskSetTaskRelationFacadeMock('createRelations'),
        );

        //Act
        $tasks = $taskManager->initialize([$task]);

        //Assert
        $this->assertCount(1, $tasks);
    }

    /**
     * @return void
     */
    public function testInitializeWhenTaskSetPassed(): void
    {
        //Arrange
        $taskSet = $this->createTaskSetMock(static::NON_EXISTENT_TASK_ID);
        $taskFromTaskSet = $this->createTaskMock('task:id');
        $taskManager = new TaskManager(
            $this->createEventDispatcherMock(InitializedEvent::class, InitializedEvent::NAME),
            $this->createRepositoryMock($taskFromTaskSet, 'create'),
            $this->createTaskFromTaskSetBuilderMock($taskFromTaskSet),
            $this->createTaskSetTaskRelationFacadeMock('createRelations'),
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
            $this->createTaskFromTaskSetBuilderMock(),
            $this->createTaskSetTaskRelationFacadeMock('removeRelations'),
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
            $this->createTaskFromTaskSetBuilderMock(),
            $this->createTaskSetTaskRelationFacadeMock('updateRelations'),
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
     * @param string $taskSetId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSetMock(string $taskSetId): TaskSetInterface
    {
        $taskMock = $this->createMock(TaskSetInterface::class);
        $taskMock->method('getId')->willReturn($taskSetId);

        return $taskMock;
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
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface
     */
    protected function createTaskFromTaskSetBuilderMock(?TaskInterface $task = null): TaskFromTaskSetBuilderInterface
    {
        $taskFromTaskSetBuilder = $this->createMock(TaskFromTaskSetBuilderInterface::class);

        if ($task === null) {
            return $taskFromTaskSetBuilder;
        }

        $taskFromTaskSetBuilder->method('buildTaskFromTaskSet')->willReturn($task);

        return $taskFromTaskSetBuilder;
    }

    /**
     * @param string|null $expectedMethod
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation\TaskSetTaskRelationFacadeInterface
     */
    protected function createTaskSetTaskRelationFacadeMock(?string $expectedMethod = null): TaskSetTaskRelationFacadeInterface
    {
        $taskSetTaskRelationFacade = $this->createMock(TaskSetTaskRelationFacadeInterface::class);

        if ($expectedMethod === null) {
            return $taskSetTaskRelationFacade;
        }

        $taskSetTaskRelationFacade->expects($this->once())->method($expectedMethod);

        return $taskSetTaskRelationFacade;
    }
}
