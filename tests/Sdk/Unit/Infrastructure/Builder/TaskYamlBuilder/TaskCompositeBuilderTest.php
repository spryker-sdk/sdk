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
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\CompositeTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\CommandTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\LifecycleTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\PlaceholderTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\ScalarTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\YamlTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator;
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
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $compositeTaskBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $inMemoryStorage = new InMemoryTaskStorage();
        $commandTaskValidator = new ConverterInputDataValidator();

        $this->compositeTaskBuilder = new CompositeTaskBuilder([
            new YamlTaskBuilder([
                new ScalarTaskPartBuilder(),
                new CommandTaskPartBuilder($commandTaskValidator, $inMemoryStorage),
                new PlaceholderTaskPartBuilder($inMemoryStorage),
                new LifecycleTaskPartBuilder(new PlaceholderTaskPartBuilder($inMemoryStorage)),
            ]),
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
     * @dataProvider provideCriteriaForTypeBasedCheck
     *
     * @param array $criteriaData
     *
     * @return void
     */
    public function testBuildReturnsResultEntityBasedOnProvidedType($criteriaData): void
    {
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
    public function provideCriteriaForTypeBasedCheck(): array
    {
        return [
            [[
                'dto' => new TaskYamlCriteriaDto(TaskType::TASK_TYPE__LOCAL_CLI, [], []),
                'instanceOf' => Task::class,
            ]],
        ];
    }
}
