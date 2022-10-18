<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskSet;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetPlaceholdersBuilder;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskSet
 * @group TaskFromTaskSetBuilderTest
 */
class TaskFromTaskSetBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildsTaskWhenSubTaskIsString(): void
    {
        // Arrange
        $command = $this->createCommandMock();
        $placeholder = $this->createPlaceholderMock();
        $existingTasks = ['taskId' => $this->createTaskMock('taskId', $command, $placeholder)];
        $taskSet = $this->createTaskSetMock('taskSetId', 'taskId', null);
        $taskSetBuilder = new TaskFromTaskSetBuilder(
            $this->createTaskSetPlaceholdersBuilderMock($placeholder),
            $this->createTaskSetCommandsBuilderMock($command),
            $this->createTaskSetOverrideMapFactoryMock(),
        );

        // Act
        $task = $taskSetBuilder->buildTaskFromTaskSet($taskSet, $existingTasks);

        // Assert
        $this->assertSame($command, $task->getCommands()[0]);
        $this->assertSame($placeholder, $task->getPlaceholders()[0]);
        $this->assertSame('taskSetId', $task->getId());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionTaskWhenSubTaskIdIsNotFound(): void
    {
        // Arrange
        $command = $this->createCommandMock();
        $placeholder = $this->createPlaceholderMock();
        $existingTasks = ['NotExistentTaskId' => $this->createTaskMock('taskId', $command, $placeholder)];
        $taskSet = $this->createTaskSetMock('taskSetId', 'taskId', null);
        $taskSetBuilder = new TaskFromTaskSetBuilder(
            $this->createTaskSetPlaceholdersBuilderMock($placeholder),
            $this->createTaskSetCommandsBuilderMock($command),
            $this->createTaskSetOverrideMapFactoryMock(),
        );

        // Act
        $this->expectException(InvalidArgumentException::class);
        $taskSetBuilder->buildTaskFromTaskSet($taskSet, $existingTasks);
    }

    /**
     * @return void
     */
    public function testBuildsTaskWhenSubTaskIsObject(): void
    {
        // Arrange
        $command = $this->createCommandMock();
        $placeholder = $this->createPlaceholderMock();
        $task = $this->createTaskMock('taskId', $command, $placeholder);
        $existingTasks = ['taskId' => $task];
        $taskSet = $this->createTaskSetMock('taskSetId', null, $task);
        $taskSetBuilder = new TaskFromTaskSetBuilder(
            $this->createTaskSetPlaceholdersBuilderMock($placeholder),
            $this->createTaskSetCommandsBuilderMock($command),
            $this->createTaskSetOverrideMapFactoryMock(),
        );

        // Act
        $task = $taskSetBuilder->buildTaskFromTaskSet($taskSet, $existingTasks);

        // Assert
        $this->assertSame($command, $task->getCommands()[0]);
        $this->assertSame($placeholder, $task->getPlaceholders()[0]);
        $this->assertSame('taskSetId', $task->getId());
    }

    /**
     * @param string $taskSetId
     * @param string|null $taskId
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface|null $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSetMock(string $taskSetId, ?string $taskId, ?TaskInterface $task): TaskSetInterface
    {
        $taskMock = $this->createMock(TaskSetInterface::class);

        if ($taskId !== null) {
            $taskMock->method('getSubTasks')->willReturn([$taskId]);
        } else {
            $taskMock->method('getSubTasks')->willReturn([$task]);
        }

        $taskMock->method('getId')->willReturn($taskSetId);
        $taskMock->method('getShortDescription')->willReturn('');
        $taskMock->method('getLifecycle')->willReturn($this->createMock(LifecycleInterface::class));
        $taskMock->method('getVersion')->willReturn('');
        $taskMock->method('getHelp')->willReturn(null);
        $taskMock->method('getSuccessor')->willReturn(null);
        $taskMock->method('isDeprecated')->willReturn(false);
        $taskMock->method('isOptional')->willReturn(true);
        $taskMock->method('getStages')->willReturn([]);

        return $taskMock;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId, CommandInterface $command, PlaceholderInterface $placeholder): TaskInterface
    {
        $taskMock = $this->createMock(TaskSetInterface::class);

        $taskMock->method('getId')->willReturn($taskId);
        $taskMock->method('getCommands')->willReturn([$command]);
        $taskMock->method('getPlaceholders')->willReturn([$placeholder]);

        return $taskMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface
     */
    protected function createCommandMock(): CommandInterface
    {
        return $this->createMock(CommandInterface::class);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(): PlaceholderInterface
    {
        return $this->createMock(PlaceholderInterface::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory
     */
    protected function createTaskSetOverrideMapFactoryMock(): TaskSetOverrideMapDtoFactory
    {
        return $this->createMock(TaskSetOverrideMapDtoFactory::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetPlaceholdersBuilder
     */
    protected function createTaskSetPlaceholdersBuilderMock(PlaceholderInterface $placeholder): TaskSetPlaceholdersBuilder
    {
        $taskSetPlaceholdersBuilderMock = $this->createMock(TaskSetPlaceholdersBuilder::class);
        $taskSetPlaceholdersBuilderMock->method('buildTaskSetPlaceholders')->willReturn([$placeholder]);

        return $taskSetPlaceholdersBuilderMock;
    }

    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface $commandInterface
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder
     */
    protected function createTaskSetCommandsBuilderMock(CommandInterface $commandInterface): TaskSetCommandsBuilder
    {
        $taskSetCommandsBuilderMock = $this->createMock(TaskSetCommandsBuilder::class);
        $taskSetCommandsBuilderMock->method('buildTaskSetCommands')->willReturn([$commandInterface]);

        return $taskSetCommandsBuilderMock;
    }
}
