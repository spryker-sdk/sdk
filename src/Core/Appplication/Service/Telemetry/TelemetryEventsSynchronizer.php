<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use DateInterval;
use DateTimeImmutable;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface;
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
     * @param bool $isDebug
     * @param bool $isTelemetryEnabled
     */
    public function __construct(
        TelemetryEventRepositoryInterface $telemetryEventRepository,
        TelemetryEventSenderInterface $telemetryEventSender,
        LockFactory $lockFactory,
        bool $isDebug,
        bool $isTelemetryEnabled
    ) {
        $this->telemetryEventRepository = $telemetryEventRepository;
        $this->telemetryEventSender = $telemetryEventSender;
        $this->lockFactory = $lockFactory;
        $this->isDebug = $isDebug;
        $this->isTelemetryEnabled = $isTelemetryEnabled;
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

        $lock = $this->lockFactory->createLock(static::LOCK_KEY, static::LOCK_TTL_SEC);

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
            static::MAX_SYNCHRONIZATION_ATTEMPTS,
            new DateInterval(sprintf('P%dD', static::MAX_EVENT_TTL_DAYS)),
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
                $this->telemetryEventRepository->removeBatch($telemetryEvents);
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
        return $this->telemetryEventRepository->getTelemetryEvents(static::MAX_SYNCHRONIZATION_ATTEMPTS, static::BATCH_SIZE, $syncTimestamp);
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
        $telemetryEvent->synchronizeFailed();

        if ($telemetryEvent->getSynchronizationAttemptsCount() >= static::MAX_SYNCHRONIZATION_ATTEMPTS) {
            $this->telemetryEventRepository->remove($telemetryEvent, false);

            return;
        }

        $this->telemetryEventRepository->update($telemetryEvent, false);
    }
}
