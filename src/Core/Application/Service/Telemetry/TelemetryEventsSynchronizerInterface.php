<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Telemetry;

use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;

interface TelemetryEventsSynchronizerInterface
{
    /**
     * @param TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    public function persist(TelemetryEventInterface $telemetryEvent): void;

    /**
     * @return void
     */
    public function synchronize(): void;
}
