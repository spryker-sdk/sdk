<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

interface TelemetryEventSenderInterface
{
    /**
     * @return bool
     */
    public function pingConnection(): bool;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\TelemetrySenderException
     *
     * @return void
     */
    public function send(TelemetryEventInterface $telemetryEvent): void;
}
