<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent as DomainTelemetryEvent;
use SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent as InfrastructureTelemetryEvent;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

interface TelemetryEventMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent
     */
    public function mapToInfrastructureTelemetryEvent(TelemetryEventInterface $telemetryEvent): InfrastructureTelemetryEvent;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent
     */
    public function mapToDomainTelemetryEvent(TelemetryEventInterface $telemetryEvent): DomainTelemetryEvent;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $fromTelemetryEvent
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent|\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent $toTelemetryEvent
     *
     * @return void
     */
    public function mapTelemetryEvents(TelemetryEventInterface $fromTelemetryEvent, TelemetryEventInterface $toTelemetryEvent): void;
}
