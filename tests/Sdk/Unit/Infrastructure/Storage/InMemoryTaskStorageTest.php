<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Storage;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Storage
 * @group InMemoryTaskStorageTest
 */
class InMemoryTaskStorageTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testAddYamlTaskSetsTaskToCorrectProperty(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addYamlTask($task)
            ->getYamlTaskById($task->getId());

        // Assert
        $this->assertEquals($task, $actualTask);
    }

    /**
     * @return void
     */
    public function testAddYamlTaskOverridesExistingTaskWithSameId(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $task1 = $this->tester->createTask();
        $task2 = $this->tester->createTask(null, [$command]);
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addYamlTask($task1)
            ->addYamlTask($task2)
            ->getYamlTaskById($task1->getId());

        // Assert
        $this->assertSame($task2->getCommands(), $actualTask->getCommands());
    }

    /**
     * @return void
     */
    public function testAddPhpTaskSetsTaskToCorrectProperty(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addPhpTask($task)
            ->getPhpTaskById($task->getId());

        // Assert
        $this->assertEquals($task, $actualTask);
    }

    /**
     * @return void
     */
    public function testAddPhpTaskOverridesExistingTaskWithSameId(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $task1 = $this->tester->createTask();
        $task2 = $this->tester->createTask(null, [$command]);
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addPhpTask($task1)
            ->addPhpTask($task2)
            ->getPhpTaskById($task1->getId());

        // Assert
        $this->assertSame($task2->getCommands(), $actualTask->getCommands());
    }

    /**
     * @return void
     */
    public function testAddTaskSetSetsTaskToCorrectProperty(): void
    {
        // Arrange
        $taskSet = $this->tester->createTaskSet();
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTaskSet = $storage->addTaskSet($taskSet)
            ->getTaskSetById($taskSet->getId());

        // Assert
        $this->assertEquals($taskSet, $actualTaskSet);
    }

    /**
     * @return void
     */
    public function testAddTaskSetOverridesExistingTaskWithSameId(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $taskSet1 = $this->tester->createTaskSet();
        $taskSet2 = $this->tester->createTaskSet(['commands' => [$command]]);
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTaskSet = $storage->addTaskSet($taskSet1)
            ->addTaskSet($taskSet2)
            ->getTaskSetById($taskSet1->getId());

        // Assert
        $this->assertSame($taskSet2->getCommands(), $actualTaskSet->getCommands());
    }
}
