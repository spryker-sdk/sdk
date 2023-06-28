<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\MetricsSender;

class NewRelicMetricSenderClient implements MetricSenderClientInterface
{
    /**
     * @var string
     */
    protected string $transactionName;

    /**
     * @param string $transactionName
     */
    public function __construct(string $transactionName)
    {
        $this->transactionName = $transactionName;
    }

    /**
     * @param string $metricName
     * @param array<mixed> $payload
     *
     * @return void
     */
    public function send(string $metricName, array $payload): void
    {
        newrelic_name_transaction($this->transactionName);
        newrelic_record_custom_event($metricName, $payload);
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return extension_loaded('newrelic');
    }
}
