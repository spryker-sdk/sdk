<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Enum\Task;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\CommandTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskPartBuilder
 * @group CommandPartBuilderTest
 */
class CommandPartBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testAddPartReturnsResultTransferWithoutCommandsIfUnsupportedTaskTypeProvided(): void
    {
        // Arrange
        $convertorInputDataValidator = new ConverterInputDataValidator();
        $commandPartBuilder = new CommandTaskPartBuilder($convertorInputDataValidator, new InMemoryTaskStorage());
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
     * @dataProvider provideValidTaskData
     *
     * @param array $taskData
     *
     * @return void
     */
    public function testAddPartReturnsResultDtoWithTaskCommands(array $taskData): void
    {
        // Arrange
        $convertorInputDataValidator = new ConverterInputDataValidator();
        $commandPartBuilder = new CommandTaskPartBuilder($convertorInputDataValidator, new InMemoryTaskStorage());
        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            [],
        );

        // Act
        $actualResultDto = $commandPartBuilder->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $command = $actualResultDto->getCommands()[0];

        $this->assertSame(
            $taskData['command'],
            $command->getCommand(),
            'Command in the result dto`s command list must have the same executable command string as given parameter.',
        );
    }

    /**
     * @return array
     */
    public function provideValidTaskData(): array
    {
        return [
            [[
                'type' => Task::TASK_TYPE_LOCAL_CLI,
                'command' => 'echo "test"',
            ]],
            [[
                'type' => Task::TASK_TYPE_LOCAL_CLI_INTERACTIVE,
                'command' => 'echo "test"',
            ]],
        ];
    }
}
