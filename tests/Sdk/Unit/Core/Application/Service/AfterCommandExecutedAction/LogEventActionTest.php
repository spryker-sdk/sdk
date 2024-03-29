<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service\AfterCommandExecutedAction;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use SprykerSdk\Sdk\Core\Application\Service\AfterCommandExecutedAction\LogEventAction;
use SprykerSdk\Sdk\Core\Application\Service\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group AfterCommandExecutedAction
 * @group LogEventActionTest
 * Add your own group annotations below this line
 */
class LogEventActionTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteWithLog(): void
    {
        // Arrange
        $logEventAction = new LogEventAction($this->createEventLoggerMock($this->once()));
        $context = $this->createContextMock();

        // Act
        $result = $logEventAction->execute($this->createCommandMock(), $context);

        // Assert
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function createContextMock(): ContextInterface
    {
        $context = $this->createMock(ContextInterface::class);

        $context->method('getExitCode')
            ->willReturn(0);

        return $context;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(): TaskInterface
    {
        return $this->createMock(TaskInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(): CommandInterface
    {
        return $this->createMock(CommandInterface::class);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvocationOrder $invocationRule
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Application\Service\EventLoggerInterface
     */
    protected function createEventLoggerMock(InvocationOrder $invocationRule): EventLoggerInterface
    {
        $eventLogger = $this->createMock(EventLoggerInterface::class);
        $eventLogger->expects($invocationRule)
            ->method('logEvent');

        return $eventLogger;
    }
}
