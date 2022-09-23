<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use GuzzleHttp\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface;
use SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException;
use Symfony\Component\Serializer\SerializerInterface;

class DataLakeTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var string
     */
    protected string $dataLakeUrl;

    /**
     * @var int
     */
    protected int $timeOut;

    /**
     * @var int
     */
    protected int $connectionTimeout;

    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param string $dataLakeUrl
     * @param int $timeOut
     * @param int $connectionTimeout
     * @param bool $isDebug
     */
    public function __construct(
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        string $dataLakeUrl,
        int $timeOut,
        int $connectionTimeout,
        bool $isDebug = false
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->dataLakeUrl = trim($dataLakeUrl);
        $this->timeOut = $timeOut;
        $this->connectionTimeout = $connectionTimeout;
        $this->isDebug = $isDebug;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void
    {
        try {
            $this->httpClient->request(
                'POST',
                $this->dataLakeUrl,
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => $this->serializer->serialize($telemetryEvents, 'json'),
                    'timeout' => $this->timeOut,
                    'connect_timeout' => $this->connectionTimeout,
                ],
            );
        } catch (NetworkExceptionInterface $e) {
            throw new TelemetryServerUnreachableException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return !$this->isDebug && $this->dataLakeUrl !== '';
    }
}
