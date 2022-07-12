<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

interface TelemetryEventSenderInterface
{
    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void;
}
