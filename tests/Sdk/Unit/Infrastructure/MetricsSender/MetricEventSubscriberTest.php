<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\MetricsSender;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricEventSubscriber;
use SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientFetcher;
use SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface;
use SprykerSdk\SdkContracts\Event\MetricEventInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group MetricsSender
 * @group MetricEventSubscriberTest
 * Add your own group annotations below this line
 */
class MetricEventSubscriberTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnEventMethods(): void
    {
        // Act
        $events = MetricEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertSame($events, [MetricEventInterface::class => 'onMetricEvent']);
    }

    /**
     * @return void
     */
    public function testOnMetricEventShouldSendMetric(): void
    {
        // Arrange
        $senderClientMock = $this->createMetricSenderClientMock();
        $metricSenderClientFetcherMock = $this->createMetricSenderClientFetcherMock($senderClientMock);
        $subscriber = new MetricEventSubscriber($metricSenderClientFetcherMock);
        $eventMock = $this->createMetricEventMock();

        // Act
        $subscriber->onMetricEvent($eventMock);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface
     */
    public function createMetricSenderClientMock(): MetricSenderClientInterface
    {
        $metricSenderClient = $this->createMock(MetricSenderClientInterface::class);
        $metricSenderClient->expects($this->once())->method('send');

        return $metricSenderClient;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientInterface $metricSenderClient
     *
     * @return \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientFetcher
     */
    public function createMetricSenderClientFetcherMock(MetricSenderClientInterface $metricSenderClient): MetricSenderClientFetcher
    {
        $metricSenderClientFetcher = $this->createMock(MetricSenderClientFetcher::class);
        $metricSenderClientFetcher->method('getFirstApplicableClient')->willReturn($metricSenderClient);

        return $metricSenderClientFetcher;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Event\MetricEventInterface
     */
    public function createMetricEventMock(): MetricEventInterface
    {
        $metricEvent = $this->createMock(MetricEventInterface::class);

        $metricEvent->method('getName')->willReturn('name');
        $metricEvent->method('getPayLoad')->willReturn([]);

        return $metricEvent;
    }
}
