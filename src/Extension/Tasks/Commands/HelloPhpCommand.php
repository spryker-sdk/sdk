<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandResponseInterface;
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
     * @param array $resolvedValues
     *
     * @return CommandResponseInterface
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
}
