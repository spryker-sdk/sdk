<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use Hello\Task\HelloPhpTask;
use Hello\Task\HelloStagedTaskSet;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry;

/**
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group TaskRegistryTest
 */
class TaskRegistryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry
     */
    protected TaskRegistry $taskRegistry;

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $tasks;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->tasks = [
            'hello:php' => new HelloPhpTask(),
            'hello:php:staged_set' => new HelloStagedTaskSet(),
        ];
        $this->taskRegistry = new TaskRegistry($this->tasks);
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetShouldReturnTaskById(): void
    {
        // Arrange
        $expectedTask = $this->tasks['hello:php'];

        // Act
        $task = $this->taskRegistry->get($expectedTask->getId());

        // Assert
        $this->assertSame($expectedTask, $task);
    }

    /**
     * @return void
     */
    public function testGetAllShouldReturnAllTasks(): void
    {
        // Act
        $task = $this->taskRegistry->getAll();

        // Assert
        $this->assertSame($this->tasks, $task);
    }

    /**
     * @return void
     */
    public function testSetShouldAddTaskToRegistry(): void
    {
        // Arrange
        $id = 'violation:php:clean-report-dir';
        $expectedTask = new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class));

        // Act
        $this->taskRegistry->set($id, $expectedTask);

        // Assert
        $task = $this->taskRegistry->get('violation:php:clean-report-dir');

        $this->assertSame($expectedTask, $task);
    }
}
