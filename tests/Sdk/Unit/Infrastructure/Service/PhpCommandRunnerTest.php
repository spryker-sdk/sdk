<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Service\PhpCommandRunner;
use SprykerSdk\Sdk\Tests\Helper\Command\ExecutableErrorCommand;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class PhpCommandRunnerTest extends Unit
{
    /**
     * @return void
     */
    public function testCanHandle(): void
    {
        // Arrange
        $phpCommandRunner = new PhpCommandRunner();
        $command = $this->createMock(ExecutableCommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getType')
            ->willReturn('php');

        // Act
        $result = $phpCommandRunner->canHandle($command);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanNotHandle(): void
    {
        // Arrange
        $phpCommandRunner = new PhpCommandRunner();
        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getType')
            ->willReturn('php');

        // Act
        $result = $phpCommandRunner->canHandle($command);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        // Arrange
        $phpCommandRunner = new PhpCommandRunner();
        $command = $this->createMock(ExecutableCommandInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $command
            ->expects($this->once())
            ->method('execute')
            ->willReturn($context);

        // Act
        $resultContext = $phpCommandRunner->execute($command, $context);

        // Assert
        $this->assertSame($resultContext, $context);
    }

    /**
     * @return void
     */
    public function testExecuteWithError(): void
    {
        // Arrange
        $phpCommandRunner = new PhpCommandRunner();
        $command = $this->createMock(ExecutableErrorCommand::class);
        $context = new Context();
        $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);
        $command
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn('test');
        $command
            ->expects($this->exactly(2))
            ->method('getErrorMessage')
            ->willReturn('testMessage');
        $command
            ->expects($this->once())
            ->method('execute')
            ->willReturn($context);

        // Act
        $messages = $phpCommandRunner->execute($command, $context)->getMessages();
        $message = current($messages);
        $commandName = array_key_first($messages);

        // Assert
        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame('test', $commandName);
    }
}
