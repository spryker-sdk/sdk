<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Unit\Infrastructure\Storage;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Storage
 * @group TaskStorageTest
 * Add your own group annotations below this line
 */
class TaskStorageTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testAddTaskSetsTaskToCorrectProperty(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $storage = new TaskStorage();

        // Act
        $storage->addTask($task);
        $actualTask = $storage->getTaskById($task->getId());

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
        $task2 = $this->tester->createTask(['commands' => [$command]]);
        $storage = new TaskStorage();

        // Act
        $storage->addTask($task1);
        $storage->addTask($task2);
        $actualTask = $storage->getTaskById($task1->getId());

        // Assert
        $this->assertSame($task2->getCommands(), $actualTask->getCommands());
    }

    /**
     * @return void
     */
    public function testArrTasksCollectionReturnsEmptyCollectionIfNothingWasSet(): void
    {
        // Arrange
        $storage = new TaskStorage();

        // Act
        $collection = $storage->getArrTasksCollection();

        // Assert
        $this->assertNotNull($collection);
        $this->assertCount(
            0,
            array_merge($collection->getTasks(), $collection->getTaskSets()),
            'Collection should have no tasks or task sets if it scratch.',
        );
    }

    /**
     * @return void
     */
    public function testArrTasksCollectionReturnsCollectionIfArrCollectionWasUpdated(): void
    {
        // Arrange
        $taskId = 'test1';
        $taskSetId = 'test2';
        $storage = new TaskStorage();
        $collection = $storage->getArrTasksCollection();
        $collection->addTask(['id' => $taskId]);
        $collection->addTaskSet(['id' => $taskSetId]);
        $storage->setArrTasksCollection($collection);

        // Act
        $collection = $storage->getArrTasksCollection();

        // Assert
        $this->assertCount(
            2,
            array_merge($collection->getTasks(), $collection->getTaskSets()),
            'Collection should have exactly 1 task and task set.',
        );
    }

    /**
     * @return void
     */
    public function testHasManifestWithIdReturnsTrueIfTaskWithGivenIdExists(): void
    {
        // Arrange
        $taskId = 'test1';
        $storage = new TaskStorage();
        $collection = $storage->getArrTasksCollection();
        $collection->addTask(['id' => $taskId]);
        $storage->setArrTasksCollection($collection);

        // Act
        $isExists = $storage->hasManifestWithId($taskId);

        // Assert
        $this->assertTrue($isExists);
    }

    /**
     * @return void
     */
    public function testHasManifestWithIdReturnsTrueIfTaskSetWithGivenIdExists(): void
    {
        // Arrange
        $taskId = 'test1';
        $storage = new TaskStorage();
        $collection = $storage->getArrTasksCollection();
        $collection->addTaskSet(['id' => $taskId]);
        $storage->setArrTasksCollection($collection);

        // Act
        $isExists = $storage->hasManifestWithId($taskId);

        // Assert
        $this->assertTrue($isExists);
    }

    /**
     * @return void
     */
    public function testHasManifestWithIdReturnsTrueIfPhpTaskWithGivenIdExists(): void
    {
        // Arrange
        $taskId = 'test1';
        $task = $this->tester->createTask(['id' => $taskId]);
        $storage = new TaskStorage();
        $storage->addTask($task);

        // Act
        $isExists = $storage->hasManifestWithId($taskId);

        // Assert
        $this->assertTrue($isExists);
    }

    /**
     * @return void
     */
    public function testHasManifestWithIdReturnsFalseIfNoTaskFound(): void
    {
        // Arrange
        $taskId = 'test1';
        $storage = new TaskStorage();

        // Act
        $isExists = $storage->hasManifestWithId($taskId);

        // Assert
        $this->assertFalse($isExists);
    }
}
