<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Domain\Entity\TelemetryEvent;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Domain
 * @group Entity
 * @group TelemetryEvent
 * @group TelemetryEventTest
 * Add your own group annotations below this line
 */
class TelemetryEventTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testCreateCorrectTelemetryEventByPayload(): void
    {
        // Arrange
        $payload = $this->tester->createTelemetryEventPayload();
        $metadata = $this->tester->createTelemetryEventMetadata();

        // Act
        $telemetryEvent = new TelemetryEvent($payload, $metadata);

        // Assert
        $this->assertSame($payload->getEventName(), $telemetryEvent->getName());
        $this->assertSame($payload->getEventVersion(), $telemetryEvent->getVersion());
        $this->assertSame($payload, $telemetryEvent->getPayload());
    }

    /**
     * @return void
     */
    public function testSynchronizationFailedAction(): void
    {
        // Arrange
        $payload = $this->tester->createTelemetryEventPayload();
        $metadata = $this->tester->createTelemetryEventMetadata();
        $telemetryEvent = new TelemetryEvent($payload, $metadata);

        // Act
        $telemetryEvent->markSynchronizeFailed();

        // Assert
        $this->assertSame(1, $telemetryEvent->getSynchronizationAttemptsCount());
        $this->assertIsInt($telemetryEvent->getLastSynchronisationTimestamp());
    }
}
