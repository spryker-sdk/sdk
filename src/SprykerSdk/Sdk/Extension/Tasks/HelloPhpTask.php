<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Extension\Tasks\Commands\HelloPhpCommand;

class HelloPhpTask implements TaskInterface
{
    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'will greet php';
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'hello:php';
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new HelloPhpCommand(),
        ];
    }
}
