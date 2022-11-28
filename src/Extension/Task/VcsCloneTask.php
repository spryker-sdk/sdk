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
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Traversable;

class VcsCloneTask implements TaskInterface
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var array<string, \VcsConnector\Adapter\VcsInterface>
     */
    protected array $vcsAdapters = [];

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param iterable<string, \VcsConnector\Adapter\VcsInterface> $vcsAdapters
     */
    public function __construct(array $commands, iterable $vcsAdapters)
    {
        $this->commands = $commands;
        $this->vcsAdapters = $vcsAdapters instanceof Traversable
            ? iterator_to_array($vcsAdapters)
            : $vcsAdapters;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'Clone project';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        $vcsList = array_flip(array_keys($this->vcsAdapters));

        return [
            new Placeholder(
                '%vcs-repository%',
                'ORIGIN',
                [
                    'description' => 'Repository link',
                    'type' => ValueTypeEnum::TYPE_STRING,
                    'alias' => 'vcs-repository',
                ],
                false,
            ),
            new Placeholder(
                '%vcs%',
                'ORIGIN',
                [
                    'choiceValues' => array_keys($vcsList),
                    'defaultValue' => array_key_first($vcsList),
                    'description' => 'Select VCS',
                    'type' => ValueTypeEnum::TYPE_STRING,
                    'alias' => 'vcs',
                ],
                false,
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
        return 'vcs:clone';
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
        return '0.1.0';
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
     * @return array<string>
     */
    public function getStages(): array
    {
        return [];
    }
}
