<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use DateInterval;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

interface TelemetryEventRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function save(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function update(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param int $maxAttemptsCount
     * @param int $limit
     * @param int $maxSyncTimestamp
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface>
     */
    public function getTelemetryEvents(int $maxAttemptsCount, int $limit, int $maxSyncTimestamp): array;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function remove(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    public function removeBatch(array $telemetryEvents): void;

    /**
     * @param int $maxAttemptsCount
     * @param \DateInterval $telemetryEventTtl
     *
     * @return void
     */
    public function removeAbandonedTelemetryEvents(int $maxAttemptsCount, DateInterval $telemetryEventTtl): void;

    /**
     * @return void
     */
    public function flushAndClear(): void;
}
