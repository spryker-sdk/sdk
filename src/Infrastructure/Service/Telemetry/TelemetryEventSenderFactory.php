<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use InvalidArgumentException;

class TelemetryEventSenderFactory
{
    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @var string
     */
    protected string $dataLakeUrl;

    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface>
     */
    protected iterable $telemetryEventSenders;

    /**
     * @var bool
     */
    protected bool $isTelemetryEnabled;

    /**
     * @param bool $isDebug
     * @param string $dataLakeUrl
     * @param bool $isTelemetryEnabled
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface> $telemetryEventSenders
     */
    public function __construct(bool $isDebug, string $dataLakeUrl, bool $isTelemetryEnabled, iterable $telemetryEventSenders)
    {
        $this->isDebug = $isDebug;
        $this->dataLakeUrl = trim($dataLakeUrl);
        $this->isTelemetryEnabled = $isTelemetryEnabled;
        $this->telemetryEventSenders = $telemetryEventSenders;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface
     */
    public function getTelemetryEventSender(): TelemetryEventSenderInterface
    {
        if (!$this->isTelemetryEnabled) {
            return $this->createNullSender();
        }

        if ($this->isDebug) {
            return $this->getTelemetryEventSenderByClassName(ReportTelemetryEventSender::class);
        }

        if ($this->dataLakeUrl !== '') {
            return $this->getTelemetryEventSenderByClassName(DataLakeTelemetryEventSender::class);
        }

        return $this->createNullSender();
    }

    /**
     * @param string $senderClassName
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected function getTelemetryEventSenderByClassName(string $senderClassName): TelemetryEventSenderInterface
    {
        foreach ($this->telemetryEventSenders as $telemetryEventSender) {
            if ($telemetryEventSender instanceof $senderClassName) {
                return $telemetryEventSender;
            }
        }

        throw new InvalidArgumentException(sprintf('%s is not found', $senderClassName));
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\TelemetryEventSenderInterface
     */
    protected function createNullSender(): TelemetryEventSenderInterface
    {
        return new class implements TelemetryEventSenderInterface
        {
            /**
             * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
             *
             * @return void
             */
            public function send(array $telemetryEvents): void
            {
            }
        };
    }
}
