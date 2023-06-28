<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\MetricsSender;

use SprykerSdk\SdkContracts\Event\MetricEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MetricEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientFetcher
     */
    protected MetricSenderClientFetcher $metricSenderClientFetcher;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\MetricsSender\MetricSenderClientFetcher $metricSenderClientFetcher
     */
    public function __construct(MetricSenderClientFetcher $metricSenderClientFetcher)
    {
        $this->metricSenderClientFetcher = $metricSenderClientFetcher;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MetricEventInterface::class => 'onMetricEvent',
        ];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Event\MetricEventInterface $event
     *
     * @return void
     */
    public function onMetricEvent(MetricEventInterface $event): void
    {
        $senderClient = $this->metricSenderClientFetcher->getFirstApplicableClient();

        if ($senderClient === null) {
            return;
        }

        $senderClient->send($event->getName(), $event->getPayLoad());
    }
}
