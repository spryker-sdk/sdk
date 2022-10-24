<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\ValueResolver\BusinessModelValueResolver;
use SprykerSdk\Sdk\Extension\ValueResolver\PCSystemValueResolver;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class BusinessModelInstallerTask implements TaskInterface
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'Install business model B2B or B2C';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return [
            new Placeholder(
                '%' . BusinessModelValueResolver::ALIAS . '%',
                BusinessModelValueResolver::ID,
            ),
            new Placeholder(
                '%' . PCSystemValueResolver::ALIAS . '%',
                PCSystemValueResolver::ID,
            ),
        ];
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
        return 'project:installer:business-model';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getVersion(): string
    {
        return '0.2.0';
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
        return null;
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
