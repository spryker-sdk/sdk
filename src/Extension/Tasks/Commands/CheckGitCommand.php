<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\ConverterInterface;
use SprykerSdk\Sdk\Contracts\Entity\ErrorCommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;

class CheckGitCommand implements CommandInterface, ErrorCommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return 'git --version';
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse $commandResponse
     *
     * @return string
     */
    public function getErrorMessage(CommandResponse $commandResponse): string
    {
        return 'For using this task you should to have GIT. More details you can find https://git-scm.com/book/en/v2/Getting-Started-Installing-Git';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'local_cli';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
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
