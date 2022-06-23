<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

/**
 * Only for dev purposes
 */
class FileTelemetrySender implements TelemetryEventSenderInterface
{
    /**
     * @var string
     */
    private string $targetDir;

    /**
     * @param string $targetDir
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @return bool
     */
    public function pingConnection(): bool
    {
        return is_dir($this->targetDir) && is_writable($this->targetDir);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    public function send(TelemetryEventInterface $telemetryEvent): void
    {
        file_put_contents($this->targetDir . '/telemetry_events.log', serialize($telemetryEvent) . "\n\n", FILE_APPEND);
    }
}
