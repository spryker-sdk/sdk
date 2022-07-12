<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

interface TelemetryEventsSynchronizerInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    public function persist(TelemetryEventInterface $telemetryEvent): void;

    /**
     * @return void
     */
    public function synchronize(): void;
}
