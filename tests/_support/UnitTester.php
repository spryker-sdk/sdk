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
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\TelemetryEventPayloadInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent as DomainTelemetryEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface;
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
}
