<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder\TaskSetCommandPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskPartBuilder
 * @group TaskSetCommandPartBuilderTest
 */
class TaskSetCommandPartBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testAddPartReturnsResultTransferWithoutCommandsIfUnsupportedTaskTypeProvided(): void
    {
        // Arrange
        $convertorInputDataValidator = new ConverterInputDataValidator();
        $commandPartBuilder = new TaskSetCommandPartBuilder($convertorInputDataValidator, new InMemoryTaskStorage());
        $criteriaDto = new TaskYamlCriteriaDto(
            'unsupported_type',
            [],
            [],
        );
        $resultDto = new TaskYamlResultDto();

        // Act
        $actualResultDto = $commandPartBuilder->addPart($criteriaDto, clone $resultDto);

        // Assert
        $this->assertEquals($resultDto, $actualResultDto);
    }

    /**
     * @return void
     */
    public function testAddPartReturnsResultDtoWithTaskSetCommandsIfTaskListDataContainsTaskWithSameIdAsInTaskData(): void
    {
        // Arrange
        $taskId = 'task:do:some';
        $taskData = [
            'type' => TaskType::TASK_TYPE__TASK_SET,
            'tasks' => [
                [
                    'type' => TaskType::TASK_TYPE__LOCAL_CLI,
                    'id' => $taskId,
                    'command' => 'echo "test"',
                ],

            ],
        ];

        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            [$taskId => $taskData['tasks'][0]],
        );

        $taskStorage = $this->createMock(InMemoryTaskStorage::class);
        $taskStorage->expects($this->never())
            ->method('getYamlTaskById')
            ->with($taskId);

        // Act
        $resultDto = (new TaskSetCommandPartBuilder(new ConverterInputDataValidator(), $taskStorage))->addPart(
            $criteriaDto,
            new TaskYamlResultDto(),
        );

        // Assert
        $this->assertSame(
            $taskData['tasks'][0]['command'],
            $resultDto->getCommands()[0]->getCommand(),
            'The result dot must contain task if such exist in the taskData and in the taskListData.',
        );
    }

    /**
     * @return void
     */
    public function testAddPartReturnsResultDtoWithTaskCommandsIfTaskFoundInStorage(): void
    {
        // Arrange
        $taskId = 'task:do:some';
        $taskData = [
            'type' => TaskType::TASK_TYPE__TASK_SET,
            'tasks' => [
                [
                    'type' => TaskType::TASK_TYPE__LOCAL_CLI,
                    'id' => $taskId,
                    'command' => 'echo "test"',
                ],
            ],
        ];

        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            [],
        );

        $command = $this->tester->createCommand();
        $storedTask = $this->tester->createTask(null, [$command]);
        $taskStorage = $this->createMock(InMemoryTaskStorage::class);
        $taskStorage->expects($this->once())
            ->method('getYamlTaskById')
            ->with($taskId)
            ->willReturn($storedTask);

        // Act
        $resultDto = (new TaskSetCommandPartBuilder(new ConverterInputDataValidator(), $taskStorage))->addPart(
            $criteriaDto,
            new TaskYamlResultDto(),
        );

        // Assert
        $this->assertSame(
            $command->getCommand(),
            $resultDto->getCommands()[0]->getCommand(),
            'The result dot must contain task if such exist in the taskData and in the task storage.',
        );
    }

    /**
     * @return void
     */
    public function testAddPartThrowsExceptionIfTaskSetContainsNestedTaskSet(): void
    {
        // Assert
        $this->expectException(TaskSetNestingException::class);

        // Arrange
        $taskId = 'task:do:some';
        $taskData = [
            'type' => TaskType::TASK_TYPE__TASK_SET,
            'tasks' => [
                [
                    'type' => TaskType::TASK_TYPE__LOCAL_CLI,
                    'id' => $taskId,
                    'command' => 'echo "test"',
                ],
            ],
        ];

        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            [],
        );

        $command = $this->tester->createCommand();
        $storedTaskSet = $this->tester->createTaskSet(['commands' => [$command]]);
        $taskStorage = $this->createMock(InMemoryTaskStorage::class);
        $taskStorage->expects($this->once())
            ->method('getYamlTaskById')
            ->with($taskId)
            ->willReturn($storedTaskSet);

        // Act
        (new TaskSetCommandPartBuilder(new ConverterInputDataValidator(), $taskStorage))->addPart(
            $criteriaDto,
            new TaskYamlResultDto(),
        );
    }
}
