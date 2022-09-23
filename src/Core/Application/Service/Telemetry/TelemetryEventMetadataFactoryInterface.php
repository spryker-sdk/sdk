<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Telemetry;

use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface;

interface TelemetryEventMetadataFactoryInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface
     */
    public function createTelemetryEventMetadata(): TelemetryEventMetadataInterface;
}
