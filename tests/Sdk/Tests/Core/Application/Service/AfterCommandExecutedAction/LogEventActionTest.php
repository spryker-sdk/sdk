<?php


/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Service\AfterCommandExecutedAction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\AfterCommandExecutedAction\LogEventAction;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class LogEventActionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\AfterCommandExecutedAction\LogEventAction
     */
    protected LogEventAction $logEventAction;

    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventLogger = $this->createMock(EventLoggerInterface::class);
        $this->logEventAction = new LogEventAction($this->eventLogger);
    }

    /**
     * @return void
     */
    public function testExecuteShouldLogEventWhenSubTaskExistsInContext(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $subTask = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], [$subTask]);

        $this->eventLogger
            ->expects($this->once())
            ->method('logEvent');

        // Act
        $this->logEventAction->execute($command, $context, $subTask->getId());
    }

    /**
     * @return void
     */
    public function testExecuteShouldLogEventWhenSubTaskNotExistsInContext(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $subTask = $this->tester->createTask();
        $context = $this->tester->createContext([], [], [], [], []);

        $this->eventLogger
            ->expects($this->never())
            ->method('logEvent');

        // Act
        $this->logEventAction->execute($command, $context, $subTask->getId());
    }
}
