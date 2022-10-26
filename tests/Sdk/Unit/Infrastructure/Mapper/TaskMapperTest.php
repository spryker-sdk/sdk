<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\FileMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\LifecycleMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group TaskMapperTest
 * Add your own group annotations below this line
 */
class TaskMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapper
     */
    protected TaskMapper $taskMapper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $commandMapper = new CommandMapper(new ConverterMapper());
        $placeholderMapper = new PlaceholderMapper();

        $this->taskMapper = new TaskMapper(
            $commandMapper,
            $placeholderMapper,
            new LifecycleMapper(new RemovedEventMapper($placeholderMapper, $commandMapper, new FileMapper())),
        );
    }

    /**
     * @return void
     */
    public function testMapToInfrastructureEntityShouldReturnEntity(): void
    {
        // Arrange
        $task = $this->tester->createTask([
            'commands' => [$this->tester->createCommand()],
            'placeholders' => [$this->tester->createPlaceholder('name', 'static', true)],
        ]);

        // Act
        $result = $this->taskMapper->mapToInfrastructureEntity($task);

        // Assert
        $this->assertSame($task->getShortDescription(), $result->getShortDescription());
        $this->assertSame($task->getHelp(), $result->getHelp());
        $this->assertSame($task->getId(), $result->getId());
        $this->assertSame($task->getSuccessor(), $result->getSuccessor());
        $this->assertSame($task->isDeprecated(), $result->isDeprecated());
        $this->assertCount(count($task->getPlaceholders()), $result->getPlaceholders());
        $this->assertCount(count($task->getCommands()), $result->getCommands());
    }

    /**
     * @return void
     */
    public function testupdateInfrastructureEntityShouldReturnEntity(): void
    {
        // Arrange
        $task = $this->tester->createTask([
            'commands' => [$this->tester->createCommand()],
            'placeholders' => [$this->tester->createPlaceholder('name', 'static', true)],
        ]);

        $infraTask = $this->tester->createInfrastructureTask();

        // Act
        $result = $this->taskMapper->updateInfrastructureEntity($task, $infraTask);

        // Assert
        $this->assertSame($task->getShortDescription(), $result->getShortDescription());
        $this->assertSame($task->getHelp(), $result->getHelp());
        $this->assertSame($task->getId(), $result->getId());
        $this->assertSame($task->getSuccessor(), $result->getSuccessor());
        $this->assertSame($task->getVersion(), $result->getVersion());
        $this->assertCount(count($task->getPlaceholders()), $result->getPlaceholders());
        $this->assertCount(count($task->getCommands()), $result->getCommands());
    }
}
