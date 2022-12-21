<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Presentation\Console\Command\TaskLoader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation;
use SprykerSdk\Sdk\Presentation\Console\Command\TaskLoader\TaskHelpMessageDecorator;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Presentation
 * @group Console
 * @group Command
 * @group TaskLoader
 * @group TaskHelpMessageDecoratorTest
 * Add your own group annotations below this line
 */
class TaskHelpMessageDecoratorTest extends Unit
{
    /**
     * @return void
     */
    public function testDecorateHelpMessageShouldReturnEmptyWhenHelpIsHullAndNoRelations(): void
    {
        // Arrange
        $taskMock = $this->createTaskMock('test:task');
        $relationRepositoryMock = $this->createTaskSetTaskRelationRepositoryMock('test:task', []);
        $taskHelpMessageDecorator = new TaskHelpMessageDecorator($relationRepositoryMock);

        // Act
        $helpMessage = $taskHelpMessageDecorator->decorateHelpMessage($taskMock);

        // Assert
        $this->assertSame('', $helpMessage);
    }

    /**
     * @return void
     */
    public function testDecorateHelpMessageShouldReturnTaskHelpWhenTaskHasNoRelations(): void
    {
        // Arrange
        $taskMock = $this->createTaskMock('test:task', 'some help message');
        $relationRepositoryMock = $this->createTaskSetTaskRelationRepositoryMock('test:task', []);
        $taskHelpMessageDecorator = new TaskHelpMessageDecorator($relationRepositoryMock);

        // Act
        $helpMessage = $taskHelpMessageDecorator->decorateHelpMessage($taskMock);

        // Assert
        $this->assertSame('some help message', $helpMessage);
    }

    /**
     * @return void
     */
    public function testDecorateHelpMessageShouldReturnHelpMessageWithSubTaskRelations(): void
    {
        // Arrange
        $taskMock = $this->createTaskMock('test:task', 'some help message');
        $taskRelations = [
            new TaskSetTaskRelation(
                $this->createTaskMock('test:task'),
                $this->createTaskMock('test:sub-task', 'sub-task help'),
            ),
        ];
        $relationRepositoryMock = $this->createTaskSetTaskRelationRepositoryMock('test:task', $taskRelations);
        $taskHelpMessageDecorator = new TaskHelpMessageDecorator($relationRepositoryMock);

        // Act
        $helpMessage = $taskHelpMessageDecorator->decorateHelpMessage($taskMock);

        // Assert
        $message = <<<HELP
        some help message
        <comment>Task set sub-tasks:</comment>
        <info> - test:sub-task</info>	sub-task help
        HELP;

        $this->assertSame($message, $helpMessage);
    }

    /**
     * @param string $taskId
     * @param string|null $helpMessage
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId, ?string $helpMessage = null): TaskInterface
    {
        $task = $this->createMock(TaskInterface::class);
        $task->method('getId')->willReturn($taskId);
        $task->method('getHelp')->willReturn($helpMessage);

        return $task;
    }

    /**
     * @param string $taskId
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface> $relations
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface
     */
    protected function createTaskSetTaskRelationRepositoryMock(
        string $taskId,
        array $relations
    ): TaskSetTaskRelationRepositoryInterface {
        $taskSetTaskRelationRepository = $this->createMock(TaskSetTaskRelationRepositoryInterface::class);

        $taskSetTaskRelationRepository->method('getByTaskSetId')
            ->with($this->equalTo($taskId))
            ->willReturn($relations);

        return $taskSetTaskRelationRepository;
    }
}
