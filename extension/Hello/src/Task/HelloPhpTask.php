<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Task;

use Hello\Task\Command\HelloPhpCommand;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class HelloPhpTask implements TaskInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'will greet php';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'hello:php';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new HelloPhpCommand(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isOptional(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return '/bin/echo "hello %world% %somebody%"';
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getStages(): array
    {
        return [];
    }
}
