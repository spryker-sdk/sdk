<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

interface TelemetryEventsSynchronizerInterface
{
    /**
     * @return void
     */
    public function synchronize(): void;
}
