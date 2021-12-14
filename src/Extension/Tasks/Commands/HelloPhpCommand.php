<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class HelloPhpCommand implements ExecutableCommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @param array $resolvedValues
     *
     * @return \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface
     */
    public function execute(array $resolvedValues): CommandResponseInterface
    {
        echo 'Hello PHP';

        return new CommandResponse(true);
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return null;
    }
}
