<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;

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


    public function getId(): string
    {
        return 'hello:php';
    }

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new HelloPhpCommand(),
        ];
    }

    public function getVersion(): ?string
    {
        return '1.0.0';
    }

    public function isDeprecated(): bool
    {
        return false;
    }

    public function getSuccessor(): ?string
    {
        return '/bin/echo "hello %world% %somebody%"';
    }

    public function getLifecycle(): ?LifecycleInterface
    {
        return null;
    }
}
