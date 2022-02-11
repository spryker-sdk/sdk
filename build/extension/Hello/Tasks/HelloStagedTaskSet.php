<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Tasks;

use Hello\Tasks\Commands\GreeterCommand;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\ValueResolvers\StaticValueResolver;
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
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageA';
                }

                /**
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_a';
                }

                /**
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage A (%foo%)')];
                }

                /**
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
                 * @return string|null
                 */
                public function getHelp(): ?string
                {
                    return null;
                }

                /**
                 * @return string
                 */
                public function getVersion(): string
                {
                    return '0.1.0';
                }

                /**
                 * @return bool
                 */
                public function isDeprecated(): bool
                {
                    return false;
                }

                /**
                 * @return bool
                 */
                public function isOptional(): bool
                {
                    return false;
                }

                /**
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
            },
            new class implements StagedTaskInterface {
                /**
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageB';
                }

                /**
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_b';
                }

                /**
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage B (%bar%)')];
                }

                /**
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
                 * @return string|null
                 */
                public function getHelp(): ?string
                {
                    return null;
                }

                /**
                 * @return string
                 */
                public function getVersion(): string
                {
                    return '0.1.0';
                }

                /**
                 * @return bool
                 */
                public function isDeprecated(): bool
                {
                    return false;
                }

                /**
                 * @return bool
                 */
                public function isOptional(): bool
                {
                    return false;
                }

                /**
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
            },
            new class implements StagedTaskInterface {
                /**
                 * @return array<string>
                 */
                public function getStages(): array
                {
                    return [];
                }

                /**
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'default';
                }

                /**
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_default';
                }

                /**
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage Default')];
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
                 * @return string
                 */
                public function getVersion(): string
                {
                    return '0.1.0';
                }

                /**
                 * @return bool
                 */
                public function isDeprecated(): bool
                {
                    return false;
                }

                /**
                 * @return bool
                 */
                public function isOptional(): bool
                {
                    return false;
                }

                /**
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
            },
        ];
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '0.1.0';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return false;
    }

    /**
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
}
