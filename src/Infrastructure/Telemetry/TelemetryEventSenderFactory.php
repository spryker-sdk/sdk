<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Telemetry;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface;

class TelemetryEventSenderFactory
{
    /**
     * @var string
     */
    protected string $telemetryTransportName;

    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface>
     */
    protected iterable $telemetryEventSenders;

    /**
     * @var bool
     */
    protected bool $isTelemetryEnabled;

    /**
     * @param string $telemetryTransportName
     * @param bool $isTelemetryEnabled
     * @param iterable<\SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface> $telemetryEventSenders
     */
    public function __construct(string $telemetryTransportName, bool $isTelemetryEnabled, iterable $telemetryEventSenders)
    {
        $this->telemetryTransportName = $telemetryTransportName;
        $this->isTelemetryEnabled = $isTelemetryEnabled;
        $this->telemetryEventSenders = $telemetryEventSenders;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface
     */
    public function getTelemetryEventSender(): TelemetryEventSenderInterface
    {
        $transportName = $this->isTelemetryEnabled ? $this->telemetryTransportName : NullTelemetryEventSender::TRANSPORT_NAME;

        foreach ($this->telemetryEventSenders as $telemetryEventSender) {
            if ($telemetryEventSender->getTransportName() !== $transportName) {
                continue;
            }

            return $telemetryEventSender;
        }

        throw new InvalidArgumentException(sprintf('Invalid telemetry transport name %s', $transportName));
    }
}
