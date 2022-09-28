<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\InvalidTaskTypeException;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\CompositeTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\YamlTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskCompositeBuilderTest
 */
class TaskCompositeBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskBuilderInterface
     */
    protected TaskBuilderInterface $compositeTaskBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->compositeTaskBuilder = new CompositeTaskBuilder([
            new YamlTaskBuilder(),
        ]);
    }

    /**
     * @return void
     */
    public function testBuildReturnsCorrectInstance(): void
    {
        // Arrange
        $criteriaDto = new TaskYamlCriteriaDto(
            TaskType::TASK_TYPE__LOCAL_CLI,
            [],
            [],
        );

        // Act
        $resultTask = $this->compositeTaskBuilder->build($criteriaDto);

        // Assert
        $this->assertInstanceOf(
            TaskInterface::class,
            $resultTask,
            sprintf('Result task must be instance of `%s`.', TaskInterface::class),
        );
    }

    /**
     * @dataProvider provideCriteriasForTypeBasedCheck
     *
     * @param array $criteriaData
     *
     * @return void
     */
    public function testBuildReturnsResultEntityBasedOnProvidedType($criteriaData): void
    {
        //Arrange
        $concreteBuilders = [
            new YamlTaskBuilder(),
        ];

        // Act
        $resultTask = $this->compositeTaskBuilder->build($criteriaData['dto']);

        // Assert
        $this->assertInstanceOf(
            $criteriaData['instanceOf'],
            $resultTask,
            sprintf('Result task must be instance of `%s`.', $criteriaData['instanceOf']),
        );
    }

    /**
     * @return void
     */
    public function testBuildThrowsExceptionInCaseInvalidTaskTypeProvided(): void
    {
        // Assert
        $this->expectException(InvalidTaskTypeException::class);

        // Arrange

        $criteriaDto = new TaskYamlCriteriaDto(
            'invalid task type',
            [],
            [],
        );

        // Act
        $this->compositeTaskBuilder->build($criteriaDto);
    }

    /**
     * @return array
     */
    public function provideCriteriasForTypeBasedCheck(): array
    {
        return [
            [[
                'dto' => new TaskYamlCriteriaDto(TaskType::TASK_TYPE__LOCAL_CLI, [], []),
                'instanceOf' => Task::class,
            ]],
        ];
    }
}
