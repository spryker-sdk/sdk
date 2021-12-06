<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEvent;
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

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return '/bin/echo "hello %world% %somebody%"';
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface
    {
        return new Lifecycle(
            new InitializedEvent(),
            new UpdatedEvent(),
            new RemovedEvent(),
        );
    }
}
