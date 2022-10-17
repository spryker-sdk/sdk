<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\YamlTaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTaskTypeException;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskYamlBuilderTest
 */
class YamlTaskBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\YamlTaskBuilder
     */
    protected YamlTaskBuilder $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected TaskPartBuilderInterface $partBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->partBuilder = $this->createMock(TaskPartBuilderInterface::class);

        $this->taskBuilder = new YamlTaskBuilder([
            $this->partBuilder,
        ]);
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildShouldReturnBuiltTask(): void
    {
        // Arrange
        $criteria = new TaskYamlCriteriaDto('local_cli', [], []);
        $taskYamlResultDto = $this->tester->createTaskYamlResultDto();

        $this->partBuilder
            ->expects($this->once())
            ->method('addPart')
            ->willReturn($taskYamlResultDto);

        // Act
        $task = $this->taskBuilder->build($criteria);

        // Assert
        $this->assertSame($taskYamlResultDto->getScalarPart('id'), $task->getId());
        $this->assertSame($taskYamlResultDto->getScalarPart('successor'), $task->getSuccessor());
        $this->assertSame($taskYamlResultDto->getScalarPart('short_description'), $task->getShortDescription());
        $this->assertSame($taskYamlResultDto->getScalarPart('stages'), $task->getStages());
        $this->assertSame($taskYamlResultDto->getScalarPart('help'), $task->getHelp());
        $this->assertSame($taskYamlResultDto->getScalarPart('version'), $task->getVersion());
        $this->assertSame($taskYamlResultDto->getLifecycle(), $task->getLifecycle());
        $this->assertSame($taskYamlResultDto->getCommands(), $task->getCommands());
        $this->assertSame($taskYamlResultDto->getPlaceholders(), $task->getPlaceholders());
    }

    /**
     * @return void
     */
    public function testBuildWithNotApplicableTypeShouldThrowException(): void
    {
        // Arrange
        $criteria = new TaskYamlCriteriaDto('unknown', [], []);

        $this->expectException(InvalidTaskTypeException::class);

        // Act
        $this->taskBuilder->build($criteria);
    }
}
