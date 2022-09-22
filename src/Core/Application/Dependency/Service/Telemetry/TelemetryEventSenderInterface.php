<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry;

use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;

interface TelemetryEventSenderInterface
{
    /**
     * @param array<TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void;

    /**
     * @return bool
     */
    public function isApplicable(): bool;
}
