<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Telemetry;

use SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface;

class NullTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var string
     */
    public const TRANSPORT_NAME = 'null';

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    public function send(array $telemetryEvents): void
    {
    }

    /**
     * @return string
     */
    public function getTransportName(): string
    {
        return static::TRANSPORT_NAME;
    }
}
