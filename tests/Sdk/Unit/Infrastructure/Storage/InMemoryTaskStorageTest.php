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
    public function testAddTaskSetsTaskToCorrectProperty(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addTask($task)
            ->getTaskById($task->getId());

        // Assert
        $this->assertEquals($task, $actualTask);
    }

    /**
     * @return void
     */
    public function testAddTaskOverridesExistingTaskWithSameId(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $task1 = $this->tester->createTask();
        $task2 = $this->tester->createTask(null, [$command]);
        $storage = new InMemoryTaskStorage();

        // Act
        $actualTask = $storage->addTask($task1)
            ->addTask($task2)
            ->getTaskById($task1->getId());

        // Assert
        $this->assertSame($task2->getCommands(), $actualTask->getCommands());
    }
}
