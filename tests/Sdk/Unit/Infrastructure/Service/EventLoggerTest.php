<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Domain\Event\EventInterface;
use SprykerSdk\Sdk\Infrastructure\Service\Logger\EventLogger;

class EventLoggerTest extends Unit
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testApprove(): void
    {
        // Arrange
        $this->logger
            ->expects($this->once())
            ->method('info');
        $event = $this->createMock(EventInterface::class);
        $eventLogger = new EventLogger($this->logger);

        // Act
        $eventLogger->logEvent($event);
    }
}
