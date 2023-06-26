<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\MetricsSender;

interface MetricSenderClientInterface
{
    /**
     * @param string $metricName
     * @param array<mixed> $payload
     *
     * @return void
     */
    public function send(string $metricName, array $payload): void;

    /**
     * @return bool
     */
    public function isApplicable(): bool;
}
