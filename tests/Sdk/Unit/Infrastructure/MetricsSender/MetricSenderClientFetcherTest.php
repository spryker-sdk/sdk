<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\MetricsSender;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientFetcher;
use SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group MetricsSender
 * @group MetricSenderClientFetcherTest
 * Add your own group annotations below this line
 */
class MetricSenderClientFetcherTest extends Unit
{
    /**
     * @return void
     */
    public function testGetFirstApplicableClientShouldReturnFirstApplicableClient(): void
    {
        // Arrange
        $firstClient = $this->createMetricSenderClientMock(true);
        $secondClient = $this->createMetricSenderClientMock(true);
        $metricSenderClientFetcher = new MetricSenderClientFetcher([$firstClient, $secondClient]);

        // Act
        $client = $metricSenderClientFetcher->getFirstApplicableClient();

        // Assert
        $this->assertSame($firstClient, $client);
    }

    /**
     * @return void
     */
    public function testGetFirstApplicableClientShouldReturnNullIfNotApplicableClientsSet(): void
    {
        // Arrange
        $firstClient = $this->createMetricSenderClientMock(false);
        $secondClient = $this->createMetricSenderClientMock(false);
        $metricSenderClientFetcher = new MetricSenderClientFetcher([$firstClient, $secondClient]);

        // Act
        $client = $metricSenderClientFetcher->getFirstApplicableClient();

        // Assert
        $this->assertNull($client);
    }

    /**
     * @param bool $isApplicable
     *
     * @return \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface
     */
    public function createMetricSenderClientMock(bool $isApplicable): MetricSenderClientInterface
    {
        $metricSenderClient = $this->createMock(MetricSenderClientInterface::class);
        $metricSenderClient->method('isApplicable')->willReturn($isApplicable);

        return $metricSenderClient;
    }
}
