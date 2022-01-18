<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Infrastructure\Logger;

use Codeception\Test\Unit;
use Monolog\Logger;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;
use SprykerSdk\Sdk\Tests\UnitTester;

class JsonFormatterTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter
     */
    protected JsonFormatter $jsonFormatter;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->jsonFormatter = new JsonFormatter();
    }

    /**
     * @return void
     */
    public function testFormatShouldReturnJson(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $task = $this->tester->createTask();
        $isSuccessful = true;

        $event = new TaskExecutedEvent($task, $command, $isSuccessful);

        $message = 'Test message';
        $context = [
            JsonFormatter::CONTEXT_EVENT => $event,
        ];

        $record = $this->tester->getMonologRecord(Logger::WARNING, $message, $context);

        // Act
        $result = $this->jsonFormatter->format($record);

        // Assert
        $this->assertJson($result);

        $decodedResult = json_decode($result, true);

        $this->assertSame($message, $decodedResult['message']);
        $this->assertSame($event->getId(), $decodedResult['id']);
        $this->assertSame($event->getType(), $decodedResult['type']);
        $this->assertSame($event->getEvent(), $decodedResult['event']);
        $this->assertSame($event->isSuccessful(), $decodedResult['successful']);
        $this->assertSame($event->getTriggeredBy(), $decodedResult['triggered_by']);
        $this->assertSame($event->getContext(), $decodedResult['sdkContext']);
    }
}
