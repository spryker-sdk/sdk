<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use DateInterval;
use DateTimeImmutable;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\Telemetry\TelemetryEventsQueryCriteria;
use SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;
use Symfony\Component\Lock\LockFactory;
use Throwable;

class TelemetryEventsSynchronizer implements TelemetryEventsSynchronizerInterface
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
    protected TelemetryEventRepositoryInterface $telemetryEventRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected TelemetryEventSenderInterface $telemetryEventSender;

    /**
     * @var \Symfony\Component\Lock\LockFactory
     */
    protected LockFactory $lockFactory;

    /**
     * @var int
     */
    protected int $batchSize;

    /**
     * @var int
     */
    protected int $maxSynchronizationAttempts;

    /**
     * @var int
     */
    protected int $maxEventTtlDays;

    /**
     * @var int
     */
    protected int $lockTtlSec;

    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @var bool
     */
    protected bool $isTelemetryEnabled;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface $telemetryEventRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface $telemetryEventSender
     * @param \Symfony\Component\Lock\LockFactory $lockFactory
     * @param int $batchSize
     * @param int $maxSynchronizationAttempts
     * @param int $maxEventTtlDays
     * @param int $lockTtlSec
     * @param bool $isDebug
     * @param bool $isTelemetryEnabled
     */
    public function __construct(
        TelemetryEventRepositoryInterface $telemetryEventRepository,
        TelemetryEventSenderInterface $telemetryEventSender,
        LockFactory $lockFactory,
        int $batchSize,
        int $maxSynchronizationAttempts,
        int $maxEventTtlDays,
        int $lockTtlSec,
        bool $isDebug = false,
        bool $isTelemetryEnabled = true
    ) {
        $this->telemetryEventRepository = $telemetryEventRepository;
        $this->telemetryEventSender = $telemetryEventSender;
        $this->lockFactory = $lockFactory;
        $this->isDebug = $isDebug;
        $this->isTelemetryEnabled = $isTelemetryEnabled;
        $this->batchSize = $batchSize;
        $this->maxSynchronizationAttempts = $maxSynchronizationAttempts;
        $this->maxEventTtlDays = $maxEventTtlDays;
        $this->lockTtlSec = $lockTtlSec;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    public function persist(TelemetryEventInterface $telemetryEvent): void
    {
        if (!$this->isTelemetryEnabled) {
            return;
        }

        $this->telemetryEventRepository->save($telemetryEvent);
    }

    /**
     * @return void
     */
    public function synchronize(): void
    {
        if (!$this->isTelemetryEnabled) {
            return;
        }

        $this->cleanTelemetryEvents();

        $lock = $this->lockFactory->createLock(static::LOCK_KEY, $this->lockTtlSec);

        if (!$lock->acquire()) {
            return;
        }

        try {
            $this->synchronizeTelemetryEvents();
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
            $this->maxSynchronizationAttempts,
            new DateInterval(sprintf('P%dD', $this->maxEventTtlDays)),
        );
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    protected function synchronizeTelemetryEvents(): void
    {
        $syncTimestamp = (int)(new DateTimeImmutable())->format('Uu');

        while (count($telemetryEvents = $this->getTelemetryEvents($syncTimestamp)) > 0) {
            try {
                $this->telemetryEventSender->send($telemetryEvents);
                $this->telemetryEventRepository->removeTelemetryEvents($telemetryEvents);
            } catch (TelemetryServerUnreachableException $e) {
                if ($this->isDebug) {
                    throw $e;
                }

                return;
            } catch (Throwable $e) {
                $this->failTelemetryEventsSynchronization($telemetryEvents);
            }

            $this->telemetryEventRepository->flushAndClear();
        }
    }

    /**
     * @param int $syncTimestamp
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface>
     */
    protected function getTelemetryEvents(int $syncTimestamp): array
    {
        $criteria = new TelemetryEventsQueryCriteria();
        $criteria->setMaxAttemptsCount($this->maxSynchronizationAttempts);
        $criteria->setMaxSyncTimestamp($syncTimestamp);
        $criteria->setLimit($this->batchSize);

        return $this->telemetryEventRepository->getTelemetryEvents($criteria);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    protected function failTelemetryEventsSynchronization(array $telemetryEvents): void
    {
        foreach ($telemetryEvents as $telemetryEvent) {
            $this->failTelemetryEventSynchronization($telemetryEvent);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
     *
     * @return void
     */
    protected function failTelemetryEventSynchronization(TelemetryEventInterface $telemetryEvent): void
    {
        $telemetryEvent->markSynchronizeFailed();

        if ($telemetryEvent->getSynchronizationAttemptsCount() >= $this->maxSynchronizationAttempts) {
            $this->telemetryEventRepository->remove($telemetryEvent, false);

            return;
        }

        $this->telemetryEventRepository->update($telemetryEvent, false);
    }
}
