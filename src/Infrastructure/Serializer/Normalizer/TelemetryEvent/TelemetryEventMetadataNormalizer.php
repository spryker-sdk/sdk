<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Serializer\Normalizer\TelemetryEvent;

use InvalidArgumentException;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TelemetryEventMetadataNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        if (!($object instanceof TelemetryEventMetadataInterface)) {
            throw new InvalidArgumentException(sprintf('Invalid class %s', get_class($object)));
        }

        return [
            'developer_email' => $object->getDeveloperEmail(),
            'developer_github_account' => $object->getDeveloperGithubAccount(),
            'project_name' => $object->getProjectName(),
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof TelemetryEventMetadataInterface;
    }
}
