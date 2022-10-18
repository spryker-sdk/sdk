<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Workflow\TimestampedMarking;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Workflow
 * @group TimestampedMarkingActionTest
 * Add your own group annotations below this line
 */
class TimestampedMarkingActionTest extends Unit
{
    /**
     * @return void
     */
    public function testApply(): void
    {
        // Arrange
        $startTime = time();
        $timestampedMarking = new TimestampedMarking(['place1' => $startTime]);
        $timestampedMarking->mark('place2');
        $timestampedMarking->unmark('place1');

        // Act
        $places = $timestampedMarking->getPlaces();
        $time = current($places);

        // Assert
        $this->assertTrue($timestampedMarking->has('place2'));
        $this->assertFalse($timestampedMarking->has('place1'));
        $this->assertCount(1, $places);
        $this->assertGreaterThanOrEqual($time, $startTime);
        $this->assertLessThanOrEqual(time(), $startTime);
    }
}
