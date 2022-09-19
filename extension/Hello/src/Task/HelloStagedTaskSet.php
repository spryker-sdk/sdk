<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Task;

use Hello\Task\Command\GreeterCommand;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class HelloStagedTaskSet implements TaskSetInterface
{
    /**
     * @return array<string>
     */
    public function getStages(): array
    {
        return ['stageA', 'stageB'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'hello:php:staged_set';
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'will greet stages';
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
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
     * @param array<string> $tags
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getSubTasks(array $tags = []): array
    {
        return [
            new class implements StagedTaskInterface {
                /**
                 * {@inheritDoc}
                 *
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageA';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_a';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage A (%foo%)')];
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
                            '%foo%',
                            StaticValueResolver::class,
                            [
                                'name' => 'foo',
                                'defaultValue' => 'FOO',
                                'description' => 'Foo description',
                            ],
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
            },
            new class implements StagedTaskInterface {
                /**
                 * {@inheritDoc}
                 *
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageB';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_b';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage B (%bar%)')];
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
                            '%bar%',
                            StaticValueResolver::class,
                            [
                                'name' => 'bar',
                                'defaultValue' => 'BAR',
                                'description' => 'Bar description',
                            ],
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
            },
            new class implements StagedTaskInterface {
                /**
                 * {@inheritDoc}
                 *
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'default';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_default';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * {@inheritDoc}
                 *
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage Default')];
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
            },
        ];
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
     * {@inheritDoc}
     *
     * @return array<string, array<string>>
     */
    public function getTagsMap(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, bool>
     */
    public function getStopOnErrorMap(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, array>>
     */
    public function getOverridePlaceholdersMap(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, string>>
     */
    public function getSharedPlaceholdersMap(): array
    {
        return [];
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
}
