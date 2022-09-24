<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Factory;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;

class CommandFactory
{
    /**
     * @param array<string, mixed> $commandData
     * @param bool $hasStopOnError
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     * @param string $errorMessage
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    public function createFromArray(
        array $commandData,
        bool $hasStopOnError = false,
        ?ConverterInterface $converter = null,
        string $errorMessage = ''
    ): CommandInterface {
        return new Command(
            $commandData['command'],
            $commandData['type'],
            $hasStopOnError,
            $commandData['tags'] ?? [],
            $converter,
            $commandData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            $errorMessage,
        );
    }
}
