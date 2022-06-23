<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent;

use DateTimeImmutable;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface;

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
     * @var \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface
     */
    protected TelemetryEventPayloadInterface $payload;

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
    protected DateTimeImmutable $createdAt;

    /**
     * @var int
     */
    protected int $version;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface $payload
     */
    public function __construct(TelemetryEventPayloadInterface $payload)
    {
        $this->name = $payload::getEventName();
        $this->version = $payload::getLatestVersion();
        $this->payload = $payload;
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return void
     */
    public function synchronizeFailed(): void
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
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return void
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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
}
