<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service\AfterCommandExecutedAction;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use SprykerSdk\Sdk\Core\Appplication\Service\AfterCommandExecutedAction\LogEventAction;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group LogEventActionTest
 */
class LogEventActionTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteWithLog(): void
    {
        $logEventAction = new LogEventAction($this->createEventLoggerMock($this->once()));
        $context = $this->createContextMock();
        $result = $logEventAction->execute($this->createCommandMock(), $context, 'test');

        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return void
     */
    public function testExecuteWithoutLog(): void
    {
        $logEventAction = new LogEventAction($this->createEventLoggerMock($this->never()));
        $context = $this->createContextMock();
        $result = $logEventAction->execute($this->createCommandMock(), $context, '');

        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContextMock(): mixed
    {
        $context = $this->createMock(ContextInterface::class);
        $context->method('getSubTasks')
            ->willReturn(['test' => $this->createTaskMock()]);

        $context->method('getExitCode')
            ->willReturn(0);

        return $context;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(): mixed
    {
        return $this->createMock(TaskInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(): mixed
    {
        return $this->createMock(CommandInterface::class);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvocationOrder $invocationRule
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected function createEventLoggerMock(InvocationOrder $invocationRule): mixed
    {
        $eventLogger = $this->createMock(EventLoggerInterface::class);
        $eventLogger->expects($invocationRule)
            ->method('logEvent');

        return $eventLogger;
    }
}
