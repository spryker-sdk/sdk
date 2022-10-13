<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Loader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoader;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Loader
 * @group TaskYamlFileLoaderTest
 */
class TaskYamlFileLoaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoader
     */
    protected TaskYamlFileLoader $taskYamlFileLoader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector
     */
    protected TaskYamlCollector $taskYamlCollector;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->taskBuilder = $this->createMock(TaskBuilderInterface::class);
        $taskStorage = new InMemoryTaskStorage();
        $this->taskYamlCollector = $this->createMock(TaskYamlCollector::class);
        $this->taskFromYamlTaskSetBuilder = $this->createMock(TaskFromYamlTaskSetBuilderInterface::class);

        $this->taskYamlFileLoader = new TaskYamlFileLoader(
            $this->taskYamlCollector,
            $this->taskFromYamlTaskSetBuilder,
            $taskStorage,
            $this->taskBuilder,
            [new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))],
        );
    }

    /**
     * @return void
     */
    public function testFindAllShouldReturnBuiltTasks(): void
    {
        // Arrange
        $taskMock = $this->createMock(Task::class);
        $taskMock->expects($this->any())
            ->method('getId')
            ->will($this->returnCallback(function () {
                return 'test:task:' . (mt_rand(100, 99999));
            }));

        $this->taskBuilder
            ->expects($this->any())
            ->method('build')
            ->willReturn($taskMock);

        $this->taskYamlCollector
            ->expects($this->once())
            ->method('collectAll')
            ->willReturn($this->tester->createManifestCollectionDto());

        // Act
        $result = $this->taskYamlFileLoader->loadAll();

        // Assert
        $this->assertCount(4, $result);
    }
}
