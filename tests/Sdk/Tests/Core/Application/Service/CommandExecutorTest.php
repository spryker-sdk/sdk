<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\CommandExecutor;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class CommandExecutorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testExecuteWithCommandRunnerShouldExecuteCommand(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], [$task]);
        $command = $this->tester->createCommand();

        $commandRunner = $this->createMock(CommandRunnerInterface::class);

        $commandRunner
            ->expects($this->once())
            ->method('canHandle')
            ->willReturn(true);

        $commandRunner
            ->expects($this->once())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedAction = $this->createMock(AfterCommandExecutedActionInterface::class);

        $afterCommandExecutedAction
            ->expects($this->once())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedActions = [$afterCommandExecutedAction];

        $commandRunners = [$commandRunner];
        $commandExecutor = new CommandExecutor($commandRunners, $afterCommandExecutedActions);

        // Act
        $context = $commandExecutor->execute($command, $context, $task->getId());

        // Assert
        $this->assertArrayHasKey($command->getCommand(), $context->getExitCodeMap());
        $this->assertSame(ContextInterface::SUCCESS_EXIT_CODE, $context->getExitCodeMap()[$command->getCommand()]);
    }

    /**
     * @return void
     */
    public function testExecuteWithoutCommandRunnerShouldNotExecuteCommand(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], [$task]);
        $command = $this->tester->createCommand();

        $afterCommandExecutedAction = $this->createMock(AfterCommandExecutedActionInterface::class);

        $afterCommandExecutedAction
            ->expects($this->never())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedActions = [$afterCommandExecutedAction];

        $commandExecutor = new CommandExecutor([], $afterCommandExecutedActions);

        // Act
        $context = $commandExecutor->execute($command, $context, $task->getId());

        // Assert
        $this->assertEmpty($context->getExitCodeMap());
    }

    /**
     * @return void
     */
    public function testExecuteWithCommandRunnersWhichCannotHandleCommandShouldNotExecuteCommand(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], [$task]);
        $command = $this->tester->createCommand();

        $commandRunner = $this->createMock(CommandRunnerInterface::class);

        $commandRunner
            ->expects($this->once())
            ->method('canHandle')
            ->willReturn(false);

        $commandRunner
            ->expects($this->never())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedAction = $this->createMock(AfterCommandExecutedActionInterface::class);

        $afterCommandExecutedAction
            ->expects($this->never())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedActions = [$afterCommandExecutedAction];

        $commandRunners = [$commandRunner];
        $commandExecutor = new CommandExecutor($commandRunners, $afterCommandExecutedActions);

        // Act
        $context = $commandExecutor->execute($command, $context, $task->getId());

        // Assert
        $this->assertEmpty($context->getExitCodeMap());
    }

    /**
     * @return void
     */
    public function testExecuteWithContextIsDryRunHasTrueShouldGatherMessages(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], [$task]);
        $command = $this->tester->createCommand();

        $context->setIsDryRun(true);

        $commandRunner = $this->createMock(CommandRunnerInterface::class);

        $commandRunner
            ->expects($this->once())
            ->method('canHandle')
            ->willReturn(true);

        $commandRunner
            ->expects($this->never())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedAction = $this->createMock(AfterCommandExecutedActionInterface::class);

        $afterCommandExecutedAction
            ->expects($this->never())
            ->method('execute')
            ->willReturn($context);

        $afterCommandExecutedActions = [$afterCommandExecutedAction];

        $commandRunners = [$commandRunner];
        $commandExecutor = new CommandExecutor($commandRunners, $afterCommandExecutedActions);

        // Act
        $context = $commandExecutor->execute($command, $context, $task->getId());

        // Assert
        $this->assertEmpty($context->getExitCodeMap());
        $this->assertArrayHasKey($command->getCommand(), $context->getMessages());
        $this->assertInstanceOf(MessageInterface::class, $context->getMessages()[$command->getCommand()]);
    }
}
