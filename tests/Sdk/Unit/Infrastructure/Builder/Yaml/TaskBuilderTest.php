<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry;
use SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group TaskBuilderTest
 */
class TaskBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder
     */
    protected TaskBuilder $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $taskRegistry = new TaskRegistry([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $placeholderBuilder = new PlaceholderBuilder($taskRegistry, new NestedTaskSetValidator(), new PlaceholderFactory());
        $this->taskBuilder = new TaskBuilder(
            $placeholderBuilder,
            new CommandBuilder($taskRegistry, new ConverterBuilder(), new NestedTaskSetValidator(), new CommandFactory()),
            new LifecycleBuilder(
                new LifecycleEventDataBuilder(
                    new FileCollectionBuilder(),
                    new LifecycleCommandBuilder(),
                    $placeholderBuilder,
                ),
            ),
            $taskRegistry,
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildTaskByTaskYamlShouldReturnBuiltTask(): void
    {
        // Arrange
        $taskYaml = $this->tester->createTaskData();

        // Act
        $task = $this->taskBuilder->buildTaskByTaskYaml($taskYaml);

        // Assert
        $this->assertSame($taskYaml->getTaskData()['id'], $task->getId());
        $this->assertSame($taskYaml->getTaskData()['stage'], $task->getStage());
        $this->assertSame($taskYaml->getTaskData()['help'], $task->getHelp());
        $this->assertSame($taskYaml->getTaskData()['stage'], $task->getStage());
        $this->assertSame($taskYaml->getTaskData()['version'], $task->getVersion());
        $this->assertSame($taskYaml->getTaskData()['short_description'], $task->getShortDescription());
        $this->assertSame($taskYaml->getTaskData()['successor'], $task->getSuccessor());
    }

    /**
     * @return void
     */
    public function testBuildTaskByTaskSetReturnsTaskInCaseNoExistingTasksProvided(): void
    {
        // Arrange
        $taskSetYaml = $this->createTaskSet();
        $command = $this->tester->createCommand();
        $tasks = [
            $this->tester->createTask(null, [$command]),
        ];

        // Act
        $task = $this->taskBuilder->buildTaskByTaskSet($taskSetYaml, $tasks);

        // Assert
        $this->assertSame(
            $taskSetYaml->getId(),
            $task->getId(),
            'Task and task set must have the same id.',
        );
        $this->assertSame(
            $taskSetYaml->getShortDescription(),
            $task->getShortDescription(),
            'Task and task set must have the same description.',
        );
        $this->assertSame(
            $taskSetYaml->getHelp(),
            $task->getHelp(),
            'Task and task set must have the same help message.',
        );
        $this->assertSame(
            $taskSetYaml->getVersion(),
            $task->getVersion(),
            'Task and task set must have the same version.',
        );
        $this->assertSame(
            $taskSetYaml->isDeprecated(),
            $task->isDeprecated(),
            'Task and task set must have the same deprecation flag.',
        );
        $this->assertSame(
            $taskSetYaml->isOptional(),
            $task->isOptional(),
            'Task and task set must have the same isOptional flag.',
        );
        $this->assertSame(
            $taskSetYaml->getSuccessor(),
            $task->getSuccessor(),
            'Task and task set must have the same successor.',
        );
        $this->assertEquals(
            $taskSetYaml->getLifecycle(),
            $task->getLifecycle(),
            'Task and task set must have equal Lifecycle objects.',
        );
        $this->assertSame(
            $taskSetYaml->getStages(),
            $task->getStages(),
            'Task and task set must have the same stages.',
        );
    }

    /**
     * @return void
     */
    public function testBuildTaskByTaskSetReturnsTaskWithOverridedCommandsFromTaskSet(): void
    {
        // Arrange
        $taskId = 'just:do:it';

        $task = $this->tester->createTask(
            null,
            [$this->tester->createCommand()],
            [],
            $taskId,
        );

        $taskForTaskSet = $this->createTaskSetSubTask($taskId);
        /** @var \SprykerSdk\SdkContracts\Entity\TaskSetInterface|\PHPUnit\Framework\MockObject\MockObject $taskSetYaml */
        $taskSetYaml = $this->createMock(TaskSetInterface::class);
        $taskSetYaml->expects($this->any())
            ->method('getSubTasks')
            ->willReturn([$taskForTaskSet]);

        $taskSetYaml->expects($this->any())
            ->method('getId')
            ->willReturn($taskId);

        // Act
        $resultTask = $this->taskBuilder->buildTaskByTaskSet($taskSetYaml, [$task]);

        // Assert
        $this->assertNotEquals(
            $taskSetYaml->getCommands(),
            $resultTask->getCommands(),
            'Task set and result task commands must not be the equal.',
        );

        $this->assertEquals(
            $taskForTaskSet->getCommands()[0],
            $resultTask->getCommands()[0],
            'Command from task set must override original task command.',
        );
    }

    /**
     * @return void
     */
    public function testBuildTaskByTaskSetReturnsTaskWithOverridedPlaceholdersFromTaskSet(): void
    {
        // Arrange
        $taskId = 'just:do:it';
        $taskPlaceholderName = '%testName%';
        $taskSetPlaceholderName = '%testNameOverride%';

        $task = $this->tester->createTask(
            null,
            [$this->tester->createCommand()],
            [$this->createPlaceholder($taskPlaceholderName)],
            $taskId,
        );

        $taskForTaskSet = $this->createTaskSetSubTask($taskId, [$taskSetPlaceholderName]);
        /** @var \SprykerSdk\SdkContracts\Entity\TaskSetInterface|\PHPUnit\Framework\MockObject\MockObject $taskSetYaml */
        $taskSetYaml = $this->createMock(TaskSetInterface::class);
        $taskSetYaml->expects($this->any())
            ->method('getSubTasks')
            ->willReturn([$taskForTaskSet]);

        $taskSetYaml->expects($this->any())
            ->method('getId')
            ->willReturn($taskId);

        // Act
        $resultTask = $this->taskBuilder->buildTaskByTaskSet($taskSetYaml, [$task]);

        // Assert
        $this->assertNotEquals(
            $taskSetYaml->getPlaceholders(),
            $resultTask->getPlaceholders(),
            'Task set and result task placeholders must not be the equal.',
        );

        $this->assertEquals(
            $taskSetPlaceholderName,
            $resultTask->getPlaceholders()[0],
            'Placeholder from task set should override original task placeholder.',
        );
    }

    /**
     * @param string $taskId
     * @param array<string> $placeholders
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskSetSubTask(string $taskId, array $placeholders = []): TaskInterface
    {
        $command = new Command(
            'unit:tester:command',
            'cli',
            true,
            [],
            null,
            ContextInterface::DEFAULT_STAGE,
            'Error message overwritten',
        );

        return $this->tester->createTask(
            null,
            [$command],
            $placeholders,
            $taskId,
        );
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholder(string $name): PlaceholderInterface
    {
        return new Placeholder(
            $name,
            'STATIC',
        );
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSet(): TaskSetInterface
    {
        // phpcs:disable
        return new class ($this->tester) implements TaskSetInterface {
            protected $tester;

            public function __construct($tester)
            {
                $this->tester = $tester;
            }

            public function getId(): string
            {
                return 'sdktest:do:it';
            }

            public function getShortDescription(): string
            {
                return 'Test example';
            }

            public function getCommands(): array
            {
                return [
                    $this->tester->createCommand(),
                ];
            }

            public function getPlaceholders(): array
            {
                return [
                    $this->tester->createPlaceholder(
                        'test_setting',
                        'value_resolver_id',
                        true,
                    ),
                ];
            }

            public function getHelp(): ?string
            {
                return null;
            }

            public function getVersion(): string
            {
                return '1.0.0';
            }

            public function isDeprecated(): bool
            {
                return false;
            }

            public function isOptional(): bool
            {
                return true;
            }

            public function getSuccessor(): ?string
            {
                return null;
            }

            public function getLifecycle(): LifecycleInterface
            {
                return $this->tester->createLifecycle();
            }

            public function getStages(): array
            {
                return [];
            }

            public function getSubTasks(array $tags = []): array
            {
                return [];
            }

            public function getTagsMap(): array
            {
                return [];
            }

            public function getStopOnErrorMap(): array
            {
                return [];
            }

            public function getOverridePlaceholdersMap(): array
            {
                return [];
            }

            public function getSharedPlaceholdersMap(): array
            {
                return [];
            }
        };
        // phpcs:enable
    }
}
