<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskRegistry;
use SprykerSdk\Sdk\Core\Application\Service\TaskYamlFactory;
use SprykerSdk\Sdk\Core\Application\TaskValidator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

class CommandBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder
     */
    protected CommandBuilder $commandBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->commandBuilder = new CommandBuilder(
            new TaskRegistry([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]),
            new ConverterBuilder(),
            new TaskYamlFactory(),
            new NestedTaskSetValidator(),
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildCommandsShouldReturnSingleCommandInArray(): void
    {
        // Arrange
        $taskYaml = $this->tester->createSingleCommandData();

        // Act
        $commands = $this->commandBuilder->buildCommands($taskYaml);

        // Assert
        $this->assertNotEmpty($commands);
        $this->assertContainsOnlyInstancesOf(CommandInterface::class, $commands);
        $this->assertCount(1, $commands);
        $this->assertSame($taskYaml->getTaskData()['command'], $commands[0]->getCommand());
        $this->assertSame($taskYaml->getTaskData()['type'], $commands[0]->getType());
        $this->assertSame($taskYaml->getTaskData()['stage'], $commands[0]->getStage());
        $this->assertSame($taskYaml->getTaskData()['tags'], $commands[0]->getTags());
    }

    /**
     * @return void
     */
    public function testBuildCommandFromTaskSetShouldReturnArrayOfCommands(): void
    {
        // Arrange
        $taskYaml = $this->tester->createTaskSetData();

        // Act
        $commands = $this->commandBuilder->buildCommands($taskYaml);

        // Assert
        $this->assertNotEmpty($commands);
        $this->assertContainsOnlyInstancesOf(CommandInterface::class, $commands);
        $this->assertCount(2, $commands);
        $this->assertSame($taskYaml->getTaskListData()['task:1']['command'], $commands[0]->getCommand());
        $this->assertSame($taskYaml->getTaskListData()['task:1']['type'], $commands[0]->getType());
        $this->assertSame($taskYaml->getTaskListData()['task:1']['stage'], $commands[0]->getStage());
        $this->assertSame($taskYaml->getTaskListData()['task:1']['tags'], $commands[0]->getTags());
        $this->assertSame($taskYaml->getTaskListData()['task:2']['command'], $commands[1]->getCommand());
        $this->assertSame($taskYaml->getTaskListData()['task:2']['type'], $commands[1]->getType());
        $this->assertSame($taskYaml->getTaskListData()['task:2']['stage'], $commands[1]->getStage());
        $this->assertSame($taskYaml->getTaskListData()['task:2']['tags'], $commands[1]->getTags());
    }
}
