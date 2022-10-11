<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry;

interface TelemetryEventSenderInterface
{
    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void;

    /**
     * @return string
     */
    public function getTransportName(): string;
}
