<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Mapper\TelemetryEventMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group TelemetryEventMapperTest
 * Add your own group annotations below this line
 */
class TelemetryEventMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\TelemetryEventMapper
     */
    protected TelemetryEventMapper $mapper;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->mapper = new TelemetryEventMapper();
    }

    /**
     * @return void
     */
    public function testCorrectlyMapToInfrastructureTelemetryEvent(): void
    {
        // Arrange
        $domainTelemetryEvent = $this->tester->createDomainTelemetryEvent();

        // Act
        $infrastructureTelemetryEvent = $this->mapper->mapToInfrastructureTelemetryEvent($domainTelemetryEvent);

        // Assert
        $this->assertSame($domainTelemetryEvent->getId(), $infrastructureTelemetryEvent->getId());
        $this->assertSame($domainTelemetryEvent->getSynchronizationAttemptsCount(), $infrastructureTelemetryEvent->getSynchronizationAttemptsCount());
        $this->assertSame($domainTelemetryEvent->getLastSynchronisationTimestamp(), $infrastructureTelemetryEvent->getLastSynchronisationTimestamp());
        $this->assertSame($domainTelemetryEvent->getVersion(), $infrastructureTelemetryEvent->getVersion());
        $this->assertSame($domainTelemetryEvent->getName(), $infrastructureTelemetryEvent->getName());
        $this->assertSame($domainTelemetryEvent->getPayload(), $infrastructureTelemetryEvent->getPayload());
        $this->assertSame($domainTelemetryEvent->getTriggeredAt(), $infrastructureTelemetryEvent->getTriggeredAt());
        $this->assertSame($domainTelemetryEvent->getPayload(), $infrastructureTelemetryEvent->getPayload());
        $this->assertSame($domainTelemetryEvent->getMetadata(), $infrastructureTelemetryEvent->getMetadata());
    }

    /**
     * @return void
     */
    public function testCorrectlyMapToDomainTelemetryEvent(): void
    {
        // Arrange
        $infrastructureTelemetryEvent = $this->tester->createInfrastructureTelemetryEvent();

        // Act
        $domainTelemetryEvent = $this->mapper->mapToDomainTelemetryEvent($infrastructureTelemetryEvent);

        // Assert
        $this->assertSame($infrastructureTelemetryEvent->getId(), $domainTelemetryEvent->getId());
        $this->assertSame($infrastructureTelemetryEvent->getSynchronizationAttemptsCount(), $domainTelemetryEvent->getSynchronizationAttemptsCount());
        $this->assertSame($infrastructureTelemetryEvent->getLastSynchronisationTimestamp(), $domainTelemetryEvent->getLastSynchronisationTimestamp());
        $this->assertSame($infrastructureTelemetryEvent->getVersion(), $domainTelemetryEvent->getVersion());
        $this->assertSame($infrastructureTelemetryEvent->getName(), $domainTelemetryEvent->getName());
        $this->assertSame($infrastructureTelemetryEvent->getPayload(), $domainTelemetryEvent->getPayload());
        $this->assertSame($infrastructureTelemetryEvent->getTriggeredAt(), $domainTelemetryEvent->getTriggeredAt());
        $this->assertSame($infrastructureTelemetryEvent->getPayload(), $domainTelemetryEvent->getPayload());
        $this->assertSame($infrastructureTelemetryEvent->getMetadata(), $domainTelemetryEvent->getMetadata());
    }

    /**
     * @return void
     */
    public function testMapCorrectlyFormTelemetryEvents(): void
    {
        // Arrange
        $infrastructureTelemetryEvent = $this->tester->createInfrastructureTelemetryEvent();
        $domainTelemetryEvent = $this->tester->createDomainTelemetryEvent();

        // Act
        $this->mapper->mapIncomingTelemetryEventToExistingTelemetryEvent($infrastructureTelemetryEvent, $domainTelemetryEvent);

        // Assert
        $this->assertSame($infrastructureTelemetryEvent->getId(), $domainTelemetryEvent->getId());
        $this->assertSame($infrastructureTelemetryEvent->getSynchronizationAttemptsCount(), $domainTelemetryEvent->getSynchronizationAttemptsCount());
        $this->assertSame($infrastructureTelemetryEvent->getLastSynchronisationTimestamp(), $domainTelemetryEvent->getLastSynchronisationTimestamp());
        $this->assertSame($infrastructureTelemetryEvent->getVersion(), $domainTelemetryEvent->getVersion());
        $this->assertSame($infrastructureTelemetryEvent->getName(), $domainTelemetryEvent->getName());
        $this->assertSame($infrastructureTelemetryEvent->getPayload(), $domainTelemetryEvent->getPayload());
        $this->assertSame($infrastructureTelemetryEvent->getMetadata(), $domainTelemetryEvent->getMetadata());
        $this->assertSame($infrastructureTelemetryEvent->getTriggeredAt(), $domainTelemetryEvent->getTriggeredAt());
    }
}
