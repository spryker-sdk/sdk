<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Domain\Events;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

class TaskExecutedEventTest extends Unit
{
 /**
  * @var \SprykerSdk\Sdk\Tests\UnitTester
  */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testGettersShouldReturnCorrectValues(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $task = $this->tester->createTask();
        $isSuccessful = true;

        $event = new TaskExecutedEvent($task, $command, $isSuccessful);

        // Assert
        $this->assertSame($task->getId(), $event->getId());
        $this->assertSame($command->getType(), $event->getType());
        $this->assertSame(TaskExecutedEvent::EVENT_NAME, $event->getEvent());
        $this->assertSame($isSuccessful, $event->isSuccessful());
        $this->assertSame(TaskExecutedEvent::TRIGGERED_BY_USER, $event->getTriggeredBy());
        $this->assertSame($task->getId(), $event->getContext());
    }
}
