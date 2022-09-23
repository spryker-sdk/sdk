<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Serializer\Normalizer\TelemetryEvent;

use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\TelemetryEventPayloadInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TelemetryEventNormalizer implements NormalizerInterface
{
    /**
     * @var iterable<\Symfony\Component\Serializer\Normalizer\NormalizerInterface>
     */
    protected iterable $payloadNormalizers;

    /**
     * @var \Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    protected NormalizerInterface $metadataPayload;

    /**
     * @param iterable<\Symfony\Component\Serializer\Normalizer\NormalizerInterface> $payloadNormalizers
     * @param \Symfony\Component\Serializer\Normalizer\NormalizerInterface $metadataPayload
     */
    public function __construct(iterable $payloadNormalizers, NormalizerInterface $metadataPayload)
    {
        $this->payloadNormalizers = $payloadNormalizers;
        $this->metadataPayload = $metadataPayload;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        if (!($object instanceof TelemetryEventInterface)) {
            throw new RuntimeException(sprintf('Invalid class %s', get_class($object)));
        }

        return [
            'name' => $object->getName(),
            'version' => $object->getVersion(),
            'scope' => $object->getScope(),
            'triggered_at' => $object->getTriggeredAt()->getTimestamp(),
            'pushed_at' => (new DateTimeImmutable())->getTimestamp(),
            'payload' => $this->getPayloadNormalizer($object->getPayload())->normalize($object->getPayload()),
            'metadata' => $this->metadataPayload->normalize($object->getMetadata()),
        ];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\TelemetryEventPayloadInterface $payload
     *
     * @throws \InvalidArgumentException
     *
     * @return \Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    public function getPayloadNormalizer(TelemetryEventPayloadInterface $payload): NormalizerInterface
    {
        foreach ($this->payloadNormalizers as $normalizer) {
            if (!$normalizer->supportsNormalization($payload)) {
                continue;
            }

            return $normalizer;
        }

        throw new InvalidArgumentException(sprintf('Normalizer for "%s" payload is not found', get_class($payload)));
    }

    /**
     * @param mixed $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof TelemetryEventInterface;
    }
}
