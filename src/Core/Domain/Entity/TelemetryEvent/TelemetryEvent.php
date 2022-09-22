<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent;

use DateTimeImmutable;
use SprykerSdk\Sdk\Core\Domain\Entity\Telemetry\TelemetryEventInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Telemetry\TelemetryEventMetadataInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Telemetry\TelemetryEventPayloadInterface;

class TelemetryEvent implements TelemetryEventInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var int
     */
    protected int $version;

    /**
     * @var string
     */
    protected string $scope;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface
     */
    protected TelemetryEventPayloadInterface $payload;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface
     */
    protected TelemetryEventMetadataInterface $metadata;

    /**
     * @var int
     */
    protected int $synchronizationAttemptsCount = 0;

    /**
     * @var int|null
     */
    protected ?int $lastSynchronisationTimestamp = null;

    /**
     * @var \DateTimeImmutable
     */
    protected DateTimeImmutable $triggeredAt;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface $payload
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface $metadata
     */
    public function __construct(TelemetryEventPayloadInterface $payload, TelemetryEventMetadataInterface $metadata)
    {
        $this->name = $payload::getEventName();
        $this->version = $payload::getEventVersion();
        $this->scope = $payload::getEventScope();
        $this->payload = $payload;
        $this->metadata = $metadata;
        $this->triggeredAt = new DateTimeImmutable();
    }

    /**
     * @return void
     */
    public function markSynchronizeFailed(): void
    {
        $this->lastSynchronisationTimestamp = (int)(new DateTimeImmutable())->format('Uu');
        ++$this->synchronizationAttemptsCount;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface
     */
    public function getPayload(): TelemetryEventPayloadInterface
    {
        return $this->payload;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface $payload
     *
     * @return void
     */
    public function setPayload(TelemetryEventPayloadInterface $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface
     */
    public function getMetadata(): TelemetryEventMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface $metadata
     *
     * @return void
     */
    public function setMetadata(TelemetryEventMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return int
     */
    public function getSynchronizationAttemptsCount(): int
    {
        return $this->synchronizationAttemptsCount;
    }

    /**
     * @param int $synchronizationAttemptsCount
     *
     * @return void
     */
    public function setSynchronizationAttemptsCount(int $synchronizationAttemptsCount): void
    {
        $this->synchronizationAttemptsCount = $synchronizationAttemptsCount;
    }

    /**
     * @return int|null
     */
    public function getLastSynchronisationTimestamp(): ?int
    {
        return $this->lastSynchronisationTimestamp;
    }

    /**
     * @param int|null $lastSynchronisationTimestamp
     *
     * @return void
     */
    public function setLastSynchronisationTimestamp(?int $lastSynchronisationTimestamp): void
    {
        $this->lastSynchronisationTimestamp = $lastSynchronisationTimestamp;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getTriggeredAt(): DateTimeImmutable
    {
        return $this->triggeredAt;
    }

    /**
     * @param \DateTimeImmutable $triggeredAt
     *
     * @return void
     */
    public function setTriggeredAt(DateTimeImmutable $triggeredAt): void
    {
        $this->triggeredAt = $triggeredAt;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return void
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     *
     * @return void
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }
}
