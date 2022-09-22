<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Repository;

use DateInterval;
use SprykerSdk\Sdk\Core\Application\Dto\Telemetry\TelemetryEventsQueryCriteria;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;

interface TelemetryEventRepositoryInterface
{
    /**
     * @param TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function save(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function update(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param TelemetryEventsQueryCriteria $criteria
     *
     * @return array
     */
    public function getTelemetryEvents(TelemetryEventsQueryCriteria $criteria): array;

    /**
     * @param TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function remove(TelemetryEventInterface $telemetryEvent, bool $flush = true): void;

    /**
     * @param array<TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    public function removeTelemetryEvents(array $telemetryEvents): void;

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
