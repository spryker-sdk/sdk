<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Contracts\Entity\ConverterInterface;
use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $resolvedValues
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse
     */
    public function execute(OutputInterface $output, array $resolvedValues): CommandResponse
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
     * @return \SprykerSdk\Sdk\Contracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return null;
    }
}
