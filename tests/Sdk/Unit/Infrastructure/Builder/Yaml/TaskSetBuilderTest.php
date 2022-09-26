<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group TaskSetBuilderTest
 */
class TaskSetBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskSetBuilder
     */
    protected TaskSetBuilder $taskSetBuilder;

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
        $this->taskBuilder = $this->createMock(TaskBuilder::class);
        $this->taskSetBuilder = new TaskSetBuilder($this->taskBuilder);
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildTaskSetShouldReturnTaskSet(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $placeholders = [$this->tester->createPlaceholder('Name', 'STATIC', true)];
        /** @var \SprykerSdk\Sdk\Core\Domain\Entity\Task $subTask */
        $subTask = $this->tester->createTask(
            null,
            [],
            $placeholders,
            'sub:task',
        );
        $taskYaml = $this->tester->createTaskSetYamlData($subTask);

        $this->taskBuilder
            ->expects($this->once())
            ->method('buildTaskByTaskYaml')
            ->with($taskYaml)
            ->willReturn($task);

        // Act
        $taskSet = $this->taskSetBuilder->buildTask($taskYaml);

        // Assert
        $this->assertCount(count($placeholders), $taskSet->getPlaceholders());
    }
}
