<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Serializer\Normalizer\TelemetryEvent\Payload;

use InvalidArgumentException as InvalidArgumentExceptionInvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CommandExecutionPayloadNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     *
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     *
     * @return array
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        if (!($object instanceof CommandExecutionPayload)) {
            throw new InvalidArgumentExceptionInvalidArgumentException(sprintf('Invalid class %s', get_class($object)));
        }

        return [
            'command_name' => $object->getCommandName(),
            'command_arguments' => $object->getInputArguments(),
            'command_options' => $object->getInputOptions(),
            'command_exit_code' => $object->getExitCode(),
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null)
    {
        return $data instanceof CommandExecutionPayload;
    }
}
