<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Telemetry;

use Codeception\Test\Unit;
use Exception;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface;
use SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventsSynchronizer;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadata;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Telemetry
 * @group TelemetryEventsSynchronizerTest
 */
class TelemetryEventsSynchronizerTest extends Unit
{
    /**
     * @return void
     */
    public function testSkipSendingWhenSynchronizerIsLocked(): void
    {
        // Arrange
        $synchronizer = new TelemetryEventsSynchronizer(
            $this->createTelemetryEventRepositoryMock($this->createTelemetryEvent()),
            $this->createSenderMockThatExpectsNoCall(),
            $this->createLockFactoryMock(false),
            10,
            3,
            1,
            10,
            false,
            true,
        );

        // Act
        $synchronizer->synchronize();
    }

    /**
     * @return void
     */
    public function testSendWhenSynchronizerIsNotLocked(): void
    {
        // Arrange
        $synchronizer = new TelemetryEventsSynchronizer(
            $this->createTelemetryEventRepositoryMock($this->createTelemetryEvent()),
            $this->createSenderMockThatExpectsOneCall(),
            $this->createLockFactoryMock(true),
            10,
            3,
            1,
            10,
            false,
            true,
        );

        // Act
        $synchronizer->synchronize();
    }

    /**
     * @return void
     */
    public function testFailTelemetryEventWhenExceptionInSender(): void
    {
        // Arrange
        $synchronizer = new TelemetryEventsSynchronizer(
            $this->createTelemetryEventRepositoryMock($this->createTelemetryEventWithExpectedFailedCall()),
            $this->createSenderMockWithErrorException(),
            $this->createLockFactoryMock(true),
            10,
            3,
            1,
            10,
            false,
            true,
        );

        // Act
        $synchronizer->synchronize();
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface $telemetryEvent
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TelemetryEventRepositoryInterface
     */
    protected function createTelemetryEventRepositoryMock(TelemetryEventInterface $telemetryEvent): TelemetryEventRepositoryInterface
    {
        $repositoryMock = $this->createMock(TelemetryEventRepositoryInterface::class);
        $repositoryMock->method('getTelemetryEvents')->will($this->onConsecutiveCalls([$telemetryEvent], []));

        return $repositoryMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface
     */
    protected function createTelemetryEvent(): TelemetryEventInterface
    {
        return new TelemetryEvent(new CommandExecutionPayload('sommand', [], []), new TelemetryEventMetadata(null, null, null));
    }

    /**
     * @param bool $isLockAcquired
     *
     * @return \Symfony\Component\Lock\LockFactory
     */
    protected function createLockFactoryMock(bool $isLockAcquired): LockFactory
    {
        $lockMock = $this->createMock(LockInterface::class);
        $lockMock->method('acquire')->willReturn($isLockAcquired);

        $factoryMock = $this->createMock(LockFactory::class);
        $factoryMock->method('createLock')->willReturn($lockMock);

        return $factoryMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected function createSenderMockThatExpectsOneCall(): TelemetryEventSenderInterface
    {
        $senderMock = $this->createMock(TelemetryEventSenderInterface::class);
        $senderMock->expects($this->once())->method('send');

        return $senderMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected function createSenderMockThatExpectsNoCall(): TelemetryEventSenderInterface
    {
        $senderMock = $this->createMock(TelemetryEventSenderInterface::class);
        $senderMock->expects($this->never())->method('send');

        return $senderMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected function createSenderMockWithErrorException(): TelemetryEventSenderInterface
    {
        $senderMock = $this->createMock(TelemetryEventSenderInterface::class);
        $senderMock->expects($this->once())->method('send')->will($this->throwException(new Exception()));

        return $senderMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface
     */
    protected function createTelemetryEventWithExpectedFailedCall(): TelemetryEventInterface
    {
        $eventMock = $this->createMock(TelemetryEventInterface::class);
        $eventMock->expects($this->once())->method('markSynchronizeFailed');

        return $eventMock;
    }
}
