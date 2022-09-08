<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use SprykerSdk\Sdk\Core\Application\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface;
use SprykerSdk\Sdk\Core\Application\Service\CommandExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Infrastructure\Service\CommandRunner\CommandRunnerInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group CommandExecutorTest
 */
class CommandExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        // Arrange
        $context = $this->createContextMock();
        $commandExecutor = new CommandExecutor(
            [
                $this->createCommandRunnerMock($context, false),
                $this->createCommandRunnerMock($context),
            ],
            [$this->createAfterCommandExecutedActionMock($context, $this->once())],
        );

        // Act
        $result = $commandExecutor->execute($this->createCommandMock(), $context);

        // Assert
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return void
     */
    public function testExecuteIsDry(): void
    {
        // Arrange
        $context = $this->createContextMock(true);
        $commandExecutor = new CommandExecutor(
            [
                $this->createCommandRunnerMock($context),
                $this->createCommandRunnerMock($context),
            ],
            [$this->createAfterCommandExecutedActionMock($context, $this->never())],
        );

        // Act
        $result = $commandExecutor->execute($this->createCommandMock(), $context);

        // Assert
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(): CommandInterface
    {
        return $this->createMock(CommandInterface::class);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param bool|true $canHandle
     * @param \PHPUnit\Framework\MockObject\Rule\InvocationOrder|null $invocationRule
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface
     */
    protected function createCommandRunnerMock(
        ContextInterface $context,
        bool $canHandle = true,
        ?InvocationOrder $invocationRule = null
    ): CommandRunnerInterface {
        if ($invocationRule === null) {
            $invocationRule = $this->once();
        }
        $commandRunner = $this->createMock(CommandRunnerInterface::class);
        $commandRunner
            ->expects($invocationRule)
            ->method('canHandle')
            ->willReturn($canHandle);
        $commandRunner
            ->expects(!$canHandle || $invocationRule->isNever() || $context->isDryRun() ? $this->never() : $this->once())
            ->method('execute')
            ->willReturn($context);

        return $commandRunner;
    }

    /**
     * @param bool|false $isDry
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function createContextMock(bool $isDry = false): ContextInterface
    {
        $context = $this->createMock(ContextInterface::class);
        $context->method('isDryRun')
            ->willReturn($isDry);

        $context->method('getExitCode')
            ->willReturn(0);

        $context->expects($isDry ? $this->atLeast(2) : $this->never())
            ->method('addMessage');

        return $context;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \PHPUnit\Framework\MockObject\Rule\InvocationOrder $invocationRule
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Application\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface
     */
    protected function createAfterCommandExecutedActionMock(ContextInterface $context, InvocationOrder $invocationRule): AfterCommandExecutedActionInterface
    {
        $afterCommandExecutedAction = $this->createMock(AfterCommandExecutedActionInterface::class);
        $afterCommandExecutedAction->expects($invocationRule)
            ->method('execute')
            ->willReturn($context);

        return $afterCommandExecutedAction;
    }
}
