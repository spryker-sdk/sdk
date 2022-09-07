<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\Task;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskFromYamlTaskSetBuilder;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetCommandsBuilder;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetPlaceholdersBuilder;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetCommandsFactory;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetOverrideMapFactory;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetPlaceholdersFactory;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskFromYamlTaskSetBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildTaskFromYamlTaskSet(): void
    {
        // Arrange
        $command = $this->createCommandMock();
        $placeholder = $this->createPlaceholderMock();
        $taskSetCommandsFactory = $this->createTaskSetCommandsFactoryMock('taskId', $command);
        $taskSetPlaceholdersFactory = $this->createTaskSetPlaceholdersFactoryMock('taskId', $placeholder);
        $taskSetOverrideMapFactory = $this->createTaskSetOverrideMapFactoryMock();
        $taskSetPlaceholdersBuilder = $this->createTaskSetPlaceholdersBuilderMock($placeholder);
        $taskSetCommandsBuilder = $this->createTaskSetCommandsBuilderMock($command);
        $taskSetArray = $this->createTaskSetArray('taskSetId');

        $taskFromYamlTaskSetBuilder = new TaskFromYamlTaskSetBuilder(
            $taskSetCommandsFactory,
            $taskSetPlaceholdersFactory,
            $taskSetOverrideMapFactory,
            $taskSetPlaceholdersBuilder,
            $taskSetCommandsBuilder,
        );

        // Act
        $task = $taskFromYamlTaskSetBuilder->buildTaskFromYamlTaskSet($taskSetArray, [], []);

        // Assert
        $this->assertSame($command, $task->getCommands()[0]);
        $this->assertSame($placeholder, $task->getPlaceholders()[0]);
        $this->assertSame('taskSetId', $task->getId());
    }

    /**
     * @param string $taskSetId
     *
     * @return array<string, string>
     */
    public function createTaskSetArray(string $taskSetId): array
    {
        return [
            'id' => $taskSetId,
            'short_description' => '',
            'version' => '',
        ];
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetCommandsFactory
     */
    public function createTaskSetCommandsFactoryMock(string $taskId, CommandInterface $command): TaskSetCommandsFactory
    {
        $taskSetCommandsFactoryMock = $this->createMock(TaskSetCommandsFactory::class);

        $taskSetCommandsFactoryMock->method('getSubTasksCommands')->willReturn([$taskId => $command]);

        return $taskSetCommandsFactoryMock;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetPlaceholdersFactory
     */
    public function createTaskSetPlaceholdersFactoryMock(string $taskId, PlaceholderInterface $placeholder): TaskSetPlaceholdersFactory
    {
        $taskSetPlaceholdersFactoryMock = $this->createMock(TaskSetPlaceholdersFactory::class);

        $taskSetPlaceholdersFactoryMock->method('getSubTasksPlaceholders')->willReturn([$taskId => $placeholder]);

        return $taskSetPlaceholdersFactoryMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetOverrideMapFactory
     */
    public function createTaskSetOverrideMapFactoryMock(): TaskSetOverrideMapFactory
    {
        $taskSetOverrideMapFactoryMock = $this->createMock(TaskSetOverrideMapFactory::class);

        $taskSetOverrideMapFactoryMock->method('createTaskSetOverrideMap')->willReturn(
            $this->createMock(TaskSetOverrideMap::class),
        );

        return $taskSetOverrideMapFactoryMock;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(): CommandInterface
    {
        return $this->createMock(CommandInterface::class);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(): PlaceholderInterface
    {
        return $this->createMock(PlaceholderInterface::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetPlaceholdersBuilder
     */
    protected function createTaskSetPlaceholdersBuilderMock(PlaceholderInterface $placeholder): TaskSetPlaceholdersBuilder
    {
        $taskSetPlaceholdersBuilderMock = $this->createMock(TaskSetPlaceholdersBuilder::class);
        $taskSetPlaceholdersBuilderMock->method('buildTaskSetPlaceholders')->willReturn([$placeholder]);

        return $taskSetPlaceholdersBuilderMock;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $commandInterface
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetCommandsBuilder
     */
    protected function createTaskSetCommandsBuilderMock(CommandInterface $commandInterface): TaskSetCommandsBuilder
    {
        $taskSetCommandsBuilderMock = $this->createMock(TaskSetCommandsBuilder::class);
        $taskSetCommandsBuilderMock->method('buildTaskSetCommands')->willReturn([$commandInterface]);

        return $taskSetCommandsBuilderMock;
    }
}
