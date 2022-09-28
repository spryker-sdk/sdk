<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\MissedTaskRequiredParamException;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder\ScalarPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;

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
        $scalarPartBuilder = new ScalarPartBuilder();
        $requiredTaskData = [
            'id' => 'test',
            'short_description' => 'description',
            'version' => '0.1.0',

        ];
        $criteriaDto = new TaskYamlCriteriaDto(
            TaskType::TASK_TYPE__LOCAL_CLI,
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
        $scalarPartBuilder = new ScalarPartBuilder();
        $criteriaDto = new TaskYamlCriteriaDto(
            TaskType::TASK_TYPE__LOCAL_CLI,
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
            TaskType::TASK_TYPE__LOCAL_CLI,
            array_merge($requiredTaskData, $optionalTaskData),
            [],
        );

        // Act
        $resultTaskDto = (new ScalarPartBuilder())->addPart($criteriaDto, new TaskYamlResultDto());
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
        $scalarPartBuilder = new ScalarPartBuilder();
        $criteriaDto = new TaskYamlCriteriaDto(
            TaskType::TASK_TYPE__LOCAL_CLI,
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
        foreach (ScalarPartBuilder::OPTIONAL_KEY_TO_DEFAULT_VALUE_MAP as $key => $expectedDefaultValue) {
            $this->assertSame(
                $expectedDefaultValue,
                $scalarParts[$key],
                'Invalid default optional value for key ' . $key,
            );
        }
    }
}
