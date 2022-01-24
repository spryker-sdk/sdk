<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Domain\Events;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Events\Event;

class EventTest extends Unit
{
    /**
     * @return void
     */
    public function testGettersShouldReturnCorrectValues(): void
    {
        // Arrange
        $expectedId = 'hello:world';
        $expectedType = 'cli';
        $expectedEvent = 'executed';
        $expectedIsSuccessful = true;
        $expectedTriggeredBy = 'user';

        $event = new Event(
            $expectedId,
            $expectedType,
            $expectedEvent,
            $expectedIsSuccessful,
            $expectedTriggeredBy,
            $expectedId,
        );

        // Assert
        $this->assertSame($expectedId, $event->getId());
        $this->assertSame($expectedType, $event->getType());
        $this->assertSame($expectedEvent, $event->getEvent());
        $this->assertSame($expectedIsSuccessful, $event->isSuccessful());
        $this->assertSame($expectedTriggeredBy, $event->getTriggeredBy());
        $this->assertSame($expectedId, $event->getContext());
    }
}
