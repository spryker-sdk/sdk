<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Service\CommandRunner\LocalCliRunner;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LocalCliRunnerTest extends Unit
{
    /**
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected CommandInterface $command;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->processHelper = $this->createMock(ProcessHelper::class);
        $this->command = $this->createMock(CommandInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testSetHelperSet(): void
    {
        // Arrange
        $this->processHelper->expects($this->once())
            ->method('setHelperSet');
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $helperSet = $this->createMock(HelperSet::class);

        // Act
        $localCliRunner->setHelperSet($helperSet);
    }

    /**
     * @return void
     */
    public function testCanHandleLocalCliType(): void
    {
        // Arrange
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $this->command
            ->expects($this->once())
            ->method('getType')
            ->willReturn('local_cli');

        // Act
        $result = $localCliRunner->canHandle($this->command);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanHandleLocalCliInteractiveType(): void
    {
        // Arrange
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $this->command
            ->expects($this->once())
            ->method('getType')
            ->willReturn('local_cli_interactive');

        // Act
        $result = $localCliRunner->canHandle($this->command);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanNotHandle(): void
    {
        // Arrange
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $this->command
            ->expects($this->once())
            ->method('getType')
            ->willReturn('php');

        // Act
        $result = $localCliRunner->canHandle($this->command);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        // Arrange
        $process = $this->createMock(Process::class);
        $process->expects($this->atLeastOnce())
            ->method('getExitCode')
            ->willReturn(0);
        $process->expects($this->once())
            ->method('getOutput')
            ->willReturn('test' . PHP_EOL . 'test');
        $this->processHelper
            ->expects($this->once())
            ->method('run')
            ->willReturn($process);
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $localCliRunner->setOutput($this->createMock(OutputInterface::class));
        $this->command
            ->expects($this->atLeastOnce())
            ->method('getCommand')
            ->willReturn('php %param1% %param3%');
        $context = new Context();
        $context->setResolvedValues(['%param1%' => 'string', '%param3%' => ['string']]);

        // Act
        $result = $localCliRunner->execute($this->command, $context);

        // Assert
        $this->assertSame($context, $result);
    }

    /**
     * @return void
     */
    public function testExecuteWithError(): void
    {
        // Arrange
        $process = $this->createMock(Process::class);
        $process->expects($this->atLeastOnce())
            ->method('getExitCode')
            ->willReturn(1);
        $process->expects($this->once())
            ->method('getOutput')
            ->willReturn('test' . PHP_EOL . 'test');
        $this->processHelper
            ->expects($this->once())
            ->method('run')
            ->willReturn($process);
        $localCliRunner = new LocalCliRunner($this->processHelper);
        $localCliRunner->setOutput($this->createMock(OutputInterface::class));
        $this->command
            ->expects($this->atLeastOnce())
            ->method('getCommand')
            ->willReturn('php %param1% %param3%');
        $context = new Context();
        $context->setResolvedValues(['%param1%' => 'string', '%param3%' => ['string']]);

        // Act
        $messages = $localCliRunner->execute($this->command, $context)->getMessages();

        // Assert
        $this->assertCount(1, $messages);
        $message = current($messages);
        $commandName = array_key_first($messages);
        $this->assertInstanceOf(Message::class, current($messages));
        $this->assertSame('test', $message->getMessage());
        $this->assertSame('php %param1% %param3%', $commandName);
    }
}
