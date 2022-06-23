<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use DateInterval;
use DateTimeImmutable;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\TelemetrySenderException;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;
use Symfony\Component\Lock\LockFactory;

class TelemetryEventsSynchronizer
{
    /**
     * @var int
     */
    protected const BATCH_SIZE = 200;

    /**
     * @var int
     */
    protected const MAX_SYNCHRONIZATION_ATTEMPTS = 3;

    /**
     * @var int
     */
    protected const MAX_EVENT_TTL_DAYS = 90;

    /**
     * @var string
     */
    protected const LOCK_KEY = 'telemetry_events_sync';

    /**
     * @var int
     */
    protected const LOCK_TTL_SEC = 5 * 60;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface
     */
    private TelemetryEventRepositoryInterface $telemetryEventRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface
     */
    private TelemetryEventSenderInterface $telemetryEventSender;

    /**
     * @var \Symfony\Component\Lock\LockFactory
     */
    private LockFactory $lockFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface $telemetryEventRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface $telemetryEventSender
     * @param \Symfony\Component\Lock\LockFactory $lockFactory
     */
    public function __construct(
        TelemetryEventRepositoryInterface $telemetryEventRepository,
        TelemetryEventSenderInterface $telemetryEventSender,
        LockFactory $lockFactory
    ) {
        $this->telemetryEventRepository = $telemetryEventRepository;
        $this->telemetryEventSender = $telemetryEventSender;
        $this->lockFactory = $lockFactory;
    }

    /**
     * @return void
     */
    public function synchronize(): void
    {
        $this->cleanTelemetryEvents();

        if (!$this->telemetryEventSender->pingConnection()) {
            return;
        }

        $lock = $this->lockFactory->createLock(static::LOCK_KEY, static::LOCK_TTL_SEC);

        if (!$lock->acquire()) {
            return;
        }

        try {
            $this->sendTelemetryEvents();
        } finally {
            $lock->release();
        }
    }

    /**
     * @return void
     */
    protected function cleanTelemetryEvents(): void
    {
        $this->telemetryEventRepository->removeAbandonedTelemetryEvents(
            static::MAX_SYNCHRONIZATION_ATTEMPTS,
            new DateInterval(sprintf('P%dD', static::MAX_EVENT_TTL_DAYS)),
        );
    }

    /**
     * @return void
     */
    protected function sendTelemetryEvents(): void
    {
        $syncTimestamp = (int)(new DateTimeImmutable())->format('Uu');

        do {
            $telemetryEvents = $this->telemetryEventRepository->getTelemetryEvents(static::MAX_SYNCHRONIZATION_ATTEMPTS, static::BATCH_SIZE, $syncTimestamp);

            foreach ($telemetryEvents as $telemetryEvent) {
                $this->sendTelemetryEvent($telemetryEvent);
            }

            $telemetryEventsCount = count($telemetryEvents);

            $this->telemetryEventRepository->flushAndClear();
        } while ($telemetryEventsCount > 0);
    }

    /**
     * @thorws TelemetrySenderException
     *
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    protected function sendTelemetryEvent(TelemetryEventInterface $telemetryEvent): void
    {
        try {
            $this->telemetryEventSender->send($telemetryEvent);
            $this->telemetryEventRepository->remove($telemetryEvent, false);
        } catch (TelemetrySenderException $e) {
            $this->failSynchronization($telemetryEvent);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    protected function failSynchronization(TelemetryEventInterface $telemetryEvent): void
    {
        $telemetryEvent->synchronizeFailed();

        if ($telemetryEvent->getSynchronizationAttemptsCount() >= static::MAX_SYNCHRONIZATION_ATTEMPTS) {
            $this->telemetryEventRepository->remove($telemetryEvent, false);

            return;
        }

        $this->telemetryEventRepository->update($telemetryEvent, false);
    }
}
