<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\InvalidTaskTypeException;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\YamlTaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\CompositeTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\YamlTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskCompositeBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $compositeTaskBuilder;

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
            YamlTaskType::TYPE_TASK,
            [],
            [],
        );

        // Act
        $resultTask = $this->compositeTaskBuilder->build($criteriaDto);

        // Assert
        $this->assertInstanceOf(
            TaskInterface::class,
            $resultTask,
            sprintf('Result task must be instance of `%s`.', TaskInterface::class)
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
            sprintf('Result task must be instance of `%s`.', $criteriaData['instanceOf'])
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
                'dto' => new TaskYamlCriteriaDto(YamlTaskType::TYPE_TASK, [], []),
                'instanceOf' => Task::class,
            ]],
        ];
    }
}
