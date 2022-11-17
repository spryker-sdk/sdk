<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Lifecycle\Event;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Lifecycle
 * @group Event
 * @group InitializedEventTest
 * Add your own group annotations below this line
 */
class InitializedEventTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testGetTaskShouldReturnTask(): void
    {
        // Arrange
        $expectedTask = $this->tester->createTask();
        $event = new InitializedEvent($expectedTask);

        // Act
        $actualTask = $event->getTask();

        // Assert
        $this->assertSame($expectedTask, $actualTask);
    }
}
