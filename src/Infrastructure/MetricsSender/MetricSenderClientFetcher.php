<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\MetricsSender;

class MetricSenderClientFetcher
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface>
     */
    protected iterable $metricClients;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface> $metricClients
     */
    public function __construct(iterable $metricClients)
    {
        $this->metricClients = $metricClients;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface|null
     */
    public function getFirstApplicableClient(): ?MetricSenderClientInterface
    {
        foreach ($this->metricClients as $metricClient) {
            if (!$metricClient->isApplicable()) {
                continue;
            }

            return $metricClient;
        }

        return null;
    }
}
