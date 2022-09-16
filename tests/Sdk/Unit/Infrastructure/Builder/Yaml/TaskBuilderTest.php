<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Application\Service\TaskYamlFactory;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;

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
        $taskPool = new TaskPool([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $placeholderBuilder = new PlaceholderBuilder($taskPool);
        $this->taskBuilder = new TaskBuilder(
            $placeholderBuilder,
            new CommandBuilder($taskPool, new ConverterBuilder(), new TaskYamlFactory()),
            new LifecycleBuilder(
                new LifecycleEventDataBuilder(
                    new FileCollectionBuilder(),
                    new LifecycleCommandBuilder(),
                    $placeholderBuilder,
                ),
            ),
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildTaskShouldReturnBuiltTask(): void
    {
        // Arrange
        $taskYaml = $this->tester->createTaskData();

        // Act
        $task = $this->taskBuilder->buildTask($taskYaml);

        // Assert
        $this->assertSame($taskYaml->getTaskData()['id'], $task->getId());
        $this->assertSame($taskYaml->getTaskData()['stage'], $task->getStage());
        $this->assertSame($taskYaml->getTaskData()['help'], $task->getHelp());
        $this->assertSame($taskYaml->getTaskData()['stage'], $task->getStage());
        $this->assertSame($taskYaml->getTaskData()['version'], $task->getVersion());
        $this->assertSame($taskYaml->getTaskData()['short_description'], $task->getShortDescription());
        $this->assertSame($taskYaml->getTaskData()['successor'], $task->getSuccessor());
    }
}
