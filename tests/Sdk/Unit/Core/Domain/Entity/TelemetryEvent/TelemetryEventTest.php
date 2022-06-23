<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Domain\Entity\TelemetryEvent;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

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

        // Act
        $telemetryEvent = new TelemetryEvent($payload);

        // Assert
        $this->assertSame($payload::getEventName(), $telemetryEvent->getName());
        $this->assertSame($payload::getLatestVersion(), $telemetryEvent->getVersion());
        $this->assertSame($payload, $telemetryEvent->getPayload());
    }

    /**
     * @return void
     */
    public function testSynchronizationFailedAction(): void
    {
        // Arrange
        $payload = $this->tester->createTelemetryEventPayload();
        $telemetryEvent = new TelemetryEvent($payload);

        // Act
        $telemetryEvent->synchronizeFailed();

        // Assert
        $this->assertSame(1, $telemetryEvent->getSynchronizationAttemptsCount());
        $this->assertIsInt($telemetryEvent->getLastSynchronisationTimestamp());
    }
}
