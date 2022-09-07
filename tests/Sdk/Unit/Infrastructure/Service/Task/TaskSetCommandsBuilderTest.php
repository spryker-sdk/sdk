<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\Task;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetCommandsBuilder;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class TaskSetCommandsBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildsOverrodePlaceholdersWhenStringCommand(): void
    {
        // Arrange
        $command = $this->createCommandMock('echo %config%', 'local_cli', ['tag_a'], false);
        $overrideMap = new TaskSetOverrideMap(
            ['taskId' => true],
            ['taskId' => ['tag_b', 'tag_c']],
            [],
            [
                'taskId' => ['%config%' => ['name' => '%new_config%']],
            ],
        );

        $taskSetCommandsBuilder = new TaskSetCommandsBuilder();

        // Act
        $commands = $taskSetCommandsBuilder->buildTaskSetCommands(['taskId' => [$command]], $overrideMap);

        // Assert
        $this->assertCount(1, $commands);
        $this->assertTrue($commands[0]->hasStopOnError());
        $this->assertSame(['tag_b', 'tag_c'], $commands[0]->getTags());
        $this->assertSame('echo %new_config%', $commands[0]->getCommand());
    }

    /**
     * @return void
     */
    public function testBuildsOverrodePlaceholdersWhenPhpTypeCommand(): void
    {
        // Arrange
        $command = $this->createExecutableCommandMock('', 'php', ['tag_a'], false);
        $overrideMap = new TaskSetOverrideMap(
            ['taskId' => true],
            ['taskId' => ['tag_b', 'tag_c']],
            [],
            [],
        );

        $taskSetCommandsBuilder = new TaskSetCommandsBuilder();

        // Act
        $commands = $taskSetCommandsBuilder->buildTaskSetCommands(['taskId' => [$command]], $overrideMap);

        // Assert
        $this->assertCount(1, $commands);
        $this->assertTrue($commands[0]->hasStopOnError());
        $this->assertSame(['tag_b', 'tag_c'], $commands[0]->getTags());
    }

    /**
     * @param string $command
     * @param string $type
     * @param array $tags
     * @param bool $stopOnError
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(
        string $command,
        string $type,
        array $tags,
        bool $stopOnError
    ): CommandInterface {
        $commandMock = $this->createMock(CommandInterface::class);

        $this->addMethodMocks($commandMock, $command, $type, $tags, $stopOnError);

        return $commandMock;
    }

    /**
     * @param string $command
     * @param string $type
     * @param array $tags
     * @param bool $stopOnError
     *
     * @return \SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface
     */
    protected function createExecutableCommandMock(
        string $command,
        string $type,
        array $tags,
        bool $stopOnError
    ): ExecutableCommandInterface {
        $commandMock = $this->createMock(ExecutableCommandInterface::class);

        $this->addMethodMocks($commandMock, $command, $type, $tags, $stopOnError);

        return $commandMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface $commandMock
     * @param string $command
     * @param string $type
     * @param array $tags
     * @param bool $stopOnError
     *
     * @return void
     */
    protected function addMethodMocks(
        MockObject $commandMock,
        string $command,
        string $type,
        array $tags,
        bool $stopOnError
    ): void {
        $commandMock->method('getCommand')->willReturn($command);
        $commandMock->method('getType')->willReturn($type);
        $commandMock->method('getTags')->willReturn($tags);
        $commandMock->method('getConverter')->willReturn($this->createMock(ConverterInterface::class));
        $commandMock->method('getStage')->willReturn('');
        $commandMock->method('hasStopOnError')->willReturn($stopOnError);
    }
}
