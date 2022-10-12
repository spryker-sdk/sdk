<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\MissedTaskRequiredParamException;
use SprykerSdk\Sdk\Core\Domain\Enum\Task;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\ScalarTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskPartBuilder
 * @group ScalarPartBuilderTest
 */
class ScalarPartBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testAddPartReturnsDtoWithRequiredParameters(): void
    {
        // Arrange
        $scalarPartBuilder = new ScalarTaskPartBuilder();
        $requiredTaskData = [
            'id' => 'test',
            'short_description' => 'description',
            'version' => '0.1.0',

        ];
        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TASK_TYPE_LOCAL_CLI,
            $requiredTaskData,
            [],
        );

        // Act
        $resultTaskDto = $scalarPartBuilder->addPart($criteriaDto, new TaskYamlResultDto());
        $scalarParts = $resultTaskDto->getScalarParts();

        // Assert
        foreach ($requiredTaskData as $key => $expectedValue) {
            $this->assertSame(
                $expectedValue,
                $scalarParts[$key],
                sprintf('Actual value `%s` must be the same as expected `%s`', $scalarParts[$key], $expectedValue),
            );
        }
    }

    /**
     * @dataProvider provideMissedRequiredTaskData
     *
     * @param array $invalidTaskData
     *
     * @return void
     */
    public function testAddPartThrowsExceptionIfRequiredFieldIsMissed(array $invalidTaskData): void
    {
        // Assert
        $this->expectException(MissedTaskRequiredParamException::class);

        // Arrange
        $scalarPartBuilder = new ScalarTaskPartBuilder();
        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TASK_TYPE_LOCAL_CLI,
            $invalidTaskData,
            [],
        );

        // Act
        $scalarPartBuilder->addPart($criteriaDto, new TaskYamlResultDto());
    }

    /**
     * @return array
     */
    public function provideMissedRequiredTaskData(): array
    {
        return [
            [[
                'short_description' => 'description',
                'version' => '0.1.0',
            ]],
            [[
                'id' => 'test',
                'version' => '0.1.0',
            ]],
            [[
                'id' => 'test',
                'short_description' => 'description',
            ]],
        ];
    }

    /**
     * @return void
     */
    public function testAddPartReturnsResultDtoWithGivenPartsBeingSet(): void
    {
        // Arrange
        $requiredTaskData = [
            'id' => 'test',
            'short_description' => 'description',
            'version' => '0.1.0',
        ];
        $optionalTaskData = [
            'help' => 'help message',
            'successor' => 'just:do:it',
            'deprecated' => true,
            'stage' => 'test',
            'optional' => false,
            'stages' => ['init', 'test'],
        ];
        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TASK_TYPE_LOCAL_CLI,
            array_merge($requiredTaskData, $optionalTaskData),
            [],
        );

        // Act
        $resultTaskDto = (new ScalarTaskPartBuilder())->addPart($criteriaDto, new TaskYamlResultDto());
        $scalarParts = $resultTaskDto->getScalarParts();

        // Assert
        foreach ($optionalTaskData as $key => $expectedValue) {
            $this->assertSame(
                $expectedValue,
                $scalarParts[$key],
                'Invalid optional value for key ' . $key,
            );
        }
    }

    /**
     * @return void
     */
    public function testAddPartReturnsResultDtoWithDefaultValuesIfNoParamsProvided(): void
    {
        // Arrange
        $scalarPartBuilder = new ScalarTaskPartBuilder();
        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TASK_TYPE_LOCAL_CLI,
            [
                'id' => 'test',
                'short_description' => 'description',
                'version' => '0.1.0',
            ],
            [],
        );

        // Act
        $resultTaskDto = $scalarPartBuilder->addPart($criteriaDto, new TaskYamlResultDto());
        $scalarParts = $resultTaskDto->getScalarParts();

        // Assert
        foreach (ScalarTaskPartBuilder::OPTIONAL_KEY_TO_DEFAULT_VALUE_MAP as $key => $expectedDefaultValue) {
            $this->assertSame(
                $expectedDefaultValue,
                $scalarParts[$key],
                'Invalid default optional value for key ' . $key,
            );
        }
    }
}
