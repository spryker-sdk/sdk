<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use GuzzleHttp\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException;
use Symfony\Component\Serializer\SerializerInterface;

class DataLakeTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var int
     */
    protected const TIMEOUT_IN_SEC = 10;

    /**
     * @var int
     */
    protected const CONNECT_TIMEOUT_IN_SEC = 4;

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
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param string $dataLakeUrl
     */
    public function __construct(ClientInterface $httpClient, SerializerInterface $serializer, string $dataLakeUrl)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->dataLakeUrl = trim($dataLakeUrl);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException
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
                    'timeout' => static::TIMEOUT_IN_SEC,
                    'connect_timeout' => static::CONNECT_TIMEOUT_IN_SEC,
                ],
            );
        } catch (NetworkExceptionInterface $e) {
            throw new TelemetryServerUnreachableException($e->getMessage(), 0, $e);
        }
    }
}
