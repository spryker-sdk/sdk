<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests;

use Codeception\Actor;
use DateTimeImmutable as DateTimeImmutableDateTimeImmutable;
use Monolog\DateTimeImmutable;
use Monolog\Logger;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\TelemetryEventPayloadInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent as DomainTelemetryEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Entity\Command as InfrastructureCommand;
use SprykerSdk\Sdk\Infrastructure\Entity\File as InfrastructureFile;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle as InfrastructureLifecycle;
use SprykerSdk\Sdk\Infrastructure\Entity\Placeholder as InfrastructurePlaceholder;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;
use SprykerSdk\Sdk\Infrastructure\Entity\Task as InfrastructureTask;
use SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent as InfrastructureTelemetryEvent;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command as IdeCommand;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Option;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Param;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends Actor
{
    use _generated\UnitTesterActions;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface|null $lifecycle
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function createTask(?LifecycleInterface $lifecycle = null, array $commands = [], array $placeholders = []): TaskInterface
    {
        return new Task(
            'task',
            'short description',
            $commands,
            $lifecycle ?: new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData()),
            '0.0.1',
            $placeholders,
            'help',
            'echo "hello"',
            false,
            'default',
            true,
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Command
     */
    public function createCommand(?ConverterInterface $converter = null): Command
    {
        return new Command(
            'unit:tester:command',
            'cli',
            true,
            [],
            $converter,
            ContextInterface::DEFAULT_STAGE,
            'Error message',
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface|null $lifecycle
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function createInfrastructureTask(?LifecycleInterface $lifecycle = null): InfrastructureTask
    {
        return new InfrastructureTask(
            'task',
            'short description',
            $lifecycle ?: new InfrastructureLifecycle(new RemovedEvent()),
            '0.0.1',
        );
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command
     */
    public function createInfrastructureCommand(): InfrastructureCommand
    {
        return new InfrastructureCommand(
            'unit:tester:command',
            'cli',
            true,
            [],
            null,
        );
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\File
     */
    public function createInfrastructureFile(string $path, string $content): InfrastructureFile
    {
        return new InfrastructureFile($path, $content);
    }

    /**
     * @param string $name
     * @param string $valueResolverId
     * @param bool $isOptional
     * @param array $configuration
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder
     */
    public function createInfrastructurePlaceholder(
        string $name,
        string $valueResolverId,
        bool $isOptional,
        array $configuration = []
    ): InfrastructurePlaceholder {
        return new InfrastructurePlaceholder(
            $name,
            $valueResolverId,
            $configuration,
            $isOptional,
        );
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle
     */
    public function createLifecycle(): Lifecycle
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            $this->createRemovedEventData(),
        );
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    public function createRemovedEventData(): RemovedEventData
    {
        return new RemovedEventData(
            [$this->createCommand()],
            [$this->createPlaceholder('path', 'static', false)],
            [$this->createFile('path', 'content')],
        );
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     *
     * @return array
     */
    public function getMonologRecord(int $level = Logger::WARNING, string $message = 'test', array $context = []): array
    {
        return [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => new DateTimeImmutable(true),
            'extra' => [],
        ];
    }

    /**
     * @param string $name
     * @param string $help
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createSymfonyCommand(string $name, string $help): SymfonyCommand
    {
        $command = (new SymfonyCommand($name))->setHelp($help);

        $command->addArgument('argument1');
        $command->addOption('option1');

        return $command;
    }

    /**
     * @param string $name
     * @param array|string $shortcut
     * @param string $description
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    public function createSymfonyInputOption(string $name, $shortcut, string $description): InputOption
    {
        return new InputOption($name, $shortcut, null, $description);
    }

    /**
     * @param string $name
     * @param array|string|float|int|bool $default
     *
     * @return \Symfony\Component\Console\Input\InputArgument
     */
    public function createSymfonyInputArgument(string $name, $default): InputArgument
    {
        return new InputArgument($name, null, '', $default);
    }

    /**
     * @param string $name
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface> $params
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface> $optionsBefore
     * @param string|null $help
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command
     */
    public function createPhpStormCommand(string $name, array $params, array $optionsBefore, ?string $help): IdeCommand
    {
        return new IdeCommand($name, $params, $optionsBefore, $help);
    }

    /**
     * @param string $name
     * @param array|string|float|int|bool $defaultValue
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Param
     */
    public function createPhpStormParam(string $name, $defaultValue): Param
    {
        return new Param($name, $defaultValue);
    }

    /**
     * @param string $name
     * @param string|null $shortcut
     * @param string|null $help
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Option
     */
    public function createPhpStormOption(string $name, ?string $shortcut, ?string $help = null): Option
    {
        return new Option($name, $shortcut, $help);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function rmdir(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($path);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent
     */
    public function createInfrastructureTelemetryEvent(): InfrastructureTelemetryEvent
    {
        $telemetryEvent = new InfrastructureTelemetryEvent($this->createTelemetryEventPayload(), $this->createTelemetryEventMetadata());
        $telemetryEvent->setId(1);
        $telemetryEvent->setSynchronizationAttemptsCount(1);
        $telemetryEvent->setLastSynchronisationTimestamp((int)(new DateTimeImmutableDateTimeImmutable())->format('Uu'));

        return $telemetryEvent;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent
     */
    public function createDomainTelemetryEvent(): DomainTelemetryEvent
    {
        $telemetryEvent = new DomainTelemetryEvent($this->createTelemetryEventPayload(), $this->createTelemetryEventMetadata());
        $telemetryEvent->setId(2);
        $telemetryEvent->setSynchronizationAttemptsCount(2);
        $telemetryEvent->setLastSynchronisationTimestamp((int)(new DateTimeImmutableDateTimeImmutable())->format('Uu'));

        return $telemetryEvent;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\TelemetryEventPayloadInterface
     */
    public function createTelemetryEventPayload(): TelemetryEventPayloadInterface
    {
        return new class () implements TelemetryEventPayloadInterface
        {
            /**
             * @return string
             */
            public function getEventName(): string
            {
                return 'test_event';
            }

            /**
             * @return string
             */
            public function getEventScope(): string
            {
                return 'SDK';
            }

            /**
             * @return int
             */
            public function getEventVersion(): int
            {
                return 1;
            }
        };
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface
     */
    public function createTelemetryEventMetadata(): TelemetryEventMetadataInterface
    {
        return new class () implements TelemetryEventMetadataInterface
        {
            /**
             * @return string|null
             */
            public function getDeveloperEmail(): ?string
            {
                return 'test-dev@example.com';
            }

            /**
             * @return string|null
             */
            public function getDeveloperGithubAccount(): ?string
            {
                return bin2hex(random_bytes(6));
            }

            /**
             * @return string|null
             */
            public function getProjectName(): ?string
            {
                return 'spryker/test';
            }
        };
    }

    /**
     * @param array $seeds
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    public function createTaskSet(array $seeds = []): TaskSetInterface
    {
        //@phpcs:disable
        return new class($seeds) implements TaskSetInterface {

            protected array $seeds = [];

            /**
             * @param array $seeds
             */
            public function __construct(array $seeds)
            {
                $this->seeds = $seeds;
            }

            public function getId(): string
            {
                return $this->seeds['id'] ?? '';
            }

            public function getShortDescription(): string
            {
                return $this->seeds['short_description'] ?? '';
            }

            public function getCommands(): array
            {
                return $this->seeds['commands'] ?? [];
            }

            public function getPlaceholders(): array
            {
                return $this->seeds['placeholders'] ?? [];
            }

            public function getHelp(): ?string
            {
                return $this->seeds['help'] ?? null;
            }

            public function getVersion(): string
            {
                return $this->seeds['version'] ?? '0.1.0';
            }

            public function isDeprecated(): bool
            {
                return $this->seeds['deprecated'] ?? false;
            }

            public function isOptional(): bool
            {
                return $this->seeds['optional'] ?? true;
            }

            public function getSuccessor(): ?string
            {
                return $this->seeds['successor'] ?? null;
            }

            public function getLifecycle(): LifecycleInterface
            {
                if ($this->seeds['lifecycle'] instanceof LifecycleInterface) {
                    return $this->seeds['lifecycle'];
                }

                return new Lifecycle(
                    new InitializedEventData(),
                    new UpdatedEventData(),
                    new RemovedEventData(),
                );
            }

            public function getStages(): array
            {
                return $this->seeds['stages'] ?? [];

            }

            public function getSubTasks(array $tags = []): array
            {
                return $this->seeds['sub-tasks'] ?? [];
            }

            public function getTagsMap(): array
            {
                return $this->seeds['tags'] ?? [];
            }

            public function getStopOnErrorMap(): array
            {
                return $this->seeds['stopOnErrorMap'] ?? [];
            }

            public function getOverridePlaceholdersMap(): array
            {
                return $this->seeds['OverridePlaceholdersMap'] ?? [];
            }

            public function getSharedPlaceholdersMap(): array
            {
                return $this->seeds['sharedPlaceholdersMap'] ?? [];
            }
        };
        // phpcs:enable
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function createManifestCollectionDto(): ManifestCollectionDto
    {
        $collection = new ManifestCollectionDto();

        $collection->addTask(
            [
                'id' => 'hello:world',
                'short_description' => 'Sends greetings',
                'help' => 'Will greet the one using it',
                'stage' => 'build',
                'version' => '1.0.0',
                'deprecated' => false,
                'successor' => 'hello:php',
                'command' => '/bin/echo "hello %world% %somebody%"',
                'type' => 'local_cli',
                'placeholders' => [
                    [
                        'name' => '%world%',
                        'value_resolver' => "SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver",
                        'optional' => false,
                        'configuration' => [
                            'name' => 'world',
                            'description' => 'what is the world?',
                            'defaultValue' => 'World',
                        ],
                    ],
                    [
                        'name' => '%somebody%',
                        'value_resolver' => 'STATIC',
                        'optional' => false,
                        'configuration' => [
                            'name' => 'somebody',
                            'description' => 'Who is somebody',
                        ],
                    ],
                ],
                'lifecycle' => [
                    'INITIALIZED' => [
                        'commands' => [
                            [
                                'command' => 'echo "hello world"',
                                'type' => 'local_cli',
                            ],
                        ],
                        'files' => [
                            [
                                'path' => '%test%',
                                'content' => 'test: 3',
                            ],
                        ],
                        'placeholders' => [
                            [
                                'name' => '%project_dir%',
                                'value_resolver' => 'STATIC',
                                'optional' => true,
                                'configuration' => [
                                    'name' => 'project_dir',
                                    'description' => 'Project dir, but actually SDK dir',
                                    'defaultValue' => '/root/path',
                                ],
                            ],
                        ],
                    ],
                    'UPDATED' => [
                        'commands' => [
                            [
                                'command' => 'echo "hello world"',
                                'type' => 'local_cli',
                            ],
                        ],
                        'files' => [
                            [
                                'path' => '%test%',
                                'content' => 'test: 3',
                            ],
                        ],
                        'placeholders' => [
                            [
                                'name' => '%project_dir%',
                                'value_resolver' => 'STATIC',
                                'optional' => true,
                                'configuration' => [
                                    'name' => 'project_dir',
                                    'description' => 'Project dir, but actually SDK dir',
                                    'defaultValue' => '/root/path',
                                ],
                            ],
                        ],
                    ],
                    'REMOVED' => [
                        'commands' => [
                            [
                                'command' => 'echo "hello world"',
                                'type' => 'local_cli',
                            ],
                        ],
                        'files' => [
                            [
                                'path' => '%test%',
                                'content' => 'test: 3',
                            ],
                        ],
                        'placeholders' => [
                            [
                                'name' => '%project_dir%',
                                'value_resolver' => 'STATIC',
                                'optional' => true,
                                'configuration' => [
                                    'name' => 'project_dir',
                                    'description' => 'Project dir, but actually SDK dir',
                                    'defaultValue' => '/root/path',
                                ],
                            ],
                        ],
                    ],
                ],
                'report_converter' => [
                    'name' => 'CheckstyleViolationReportConverter',
                    'configuration' => [
                        'input_file' => 'phpcs.codestyle.json',
                        'producer' => 'phpcs',
                    ],
                ],
            ],
        );
        $collection->addTask([
            'id' => 'bye:world',
            'short_description' => 'Sends greetings',
            'help' => 'Will greet the one using it',
            'stage' => 'build',
            'version' => '1.0.0',
            'deprecated' => false,
            'successor' => 'hello:php',
            'command' => '/bin/echo "hello % world % %somebody % "',
            'type' => 'local_cli',
            'placeholders' => [
                [
                    'name' => '%world%',
                    'value_resolver' => "SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver",
                    'optional' => false,
                    'configuration' => [
                        'name' => 'world',
                        'description' => 'what is the world?',
                        'defaultValue' => 'World',
                    ],
                ],
                [
                    'name' => '%somebody%',
                    'value_resolver' => 'STATIC',
                    'optional' => false,
                    'configuration' => [
                        'name' => 'somebody',
                        'description' => 'Who is somebody',
                    ],
                ],
            ],
            'lifecycle' => [
                'INITIALIZED' => [
                    'commands' => null,
                    'files' => null,
                    'placeholders' => null,
                ],
                'UPDATED' => [
                    'commands' => null,
                    'files' => null,
                    'placeholders' => null,
                ],
                'REMOVED' => [
                    'commands' => null,
                    'files' => null,
                    'placeholders' => null,
                ],
            ],
        ]);
        $collection->addTaskSet(
            [
                'id' => 'validation:php:codestyle',
                'short_description' => 'Fixes violations and validates your php code using different approaches like codestyle. Generate report in the end.',
                'help' => 'Fixes violations and validates your php code using different approaches like codestyle. Generate report in the end.',
                'stage' => 'build',
                'version' => '1.0.0',
                'command' => null,
                'type' => 'task_set',
                'tasks' => [
                    [
                        'id' => 'hello:world',
                        'stop_on_error' => false,
                    ],
                    [
                        'id' => 'bye:world',
                        'stop_on_error' => false,
                    ],
                ],
                'placeholders' => [],
            ],
        );

        return $collection;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    public function createTaskYamlResultDto(): TaskYamlResultDto
    {
        $taskYamlResultDto = new TaskYamlResultDto();

        $taskYamlResultDto->addScalarPart('id', 'task');
        $taskYamlResultDto->addScalarPart('short_description', 'Short');
        $taskYamlResultDto->addScalarPart('version', '1.0.0');
        $taskYamlResultDto->addScalarPart('help', 'Help');
        $taskYamlResultDto->addScalarPart('successor', 'successor:task');
        $taskYamlResultDto->addScalarPart('deprecated', false);
        $taskYamlResultDto->addScalarPart('stage', 'default');
        $taskYamlResultDto->addScalarPart('optional', false);
        $taskYamlResultDto->addScalarPart('stages', []);
        $taskYamlResultDto->addCommand(new Command('echo 1', 'local_cli'));
        $taskYamlResultDto->addPlaceholder(new Placeholder('%place%', 'value_resolver', []));
        $taskYamlResultDto->setLifecycle(new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData()));

        return $taskYamlResultDto;
    }
}
