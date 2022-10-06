<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Loader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoader;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Loader
 * @group TaskYamlFileLoaderTest
 *
 * @todo :: extract part of the test to the TaskYamlReaderTest and replace it by mock.
 */
class TaskYamlFileLoaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoader
     */
    protected TaskYamlFileLoader $taskYamlFileLoader;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskBuilderInterface
     */
    protected TaskBuilderInterface $taskBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->settingRepository = $this->createMock(SettingRepository::class);
        $this->fileFinder = $this->createMock(Finder::class);
        $this->taskBuilder = $this->createMock(TaskBuilderInterface::class);
        $taskStorage = new InMemoryTaskStorage();
        $this->taskYamlFileLoader = new TaskYamlFileLoader(
            new TaskYamlReader(
                $this->settingRepository,
                new Finder(),
                new Yaml(),
            ),
            $this->createTaskFromYamlTaskSetBuilderMock(),
            $taskStorage,
            $this->taskBuilder,
            [new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))],
        );
    }

    /**
     * @return void
     */
    public function testFindAllWithoutDefinedExtensionDirsSettingShouldThrowException(): void
    {
        // Arrange
        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with('extension_dirs')
            ->willReturn(null);

        $this->expectException(MissingSettingException::class);
        $this->expectExceptionMessage('extension_dirs are not configured properly');

        // Act
        $this->taskYamlFileLoader->loadAll();
    }

    /**
     * @return void
     */
    public function testFindAllShouldReturnBuiltTasks(): void
    {
        // Arrange
        $pathToTasks = realpath(__DIR__ . '/../../../../_support/data/');

        $setting = $this->tester->createInfrastructureSetting(
            'extension_dirs',
            [$pathToTasks],
        );

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with('extension_dirs')
            ->willReturn($setting);

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

        // Act
        $result = $this->taskYamlFileLoader->loadAll();

        // Assert
        $this->assertCount(4, $result);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    public function createTaskFromYamlTaskSetBuilderMock(): TaskFromYamlTaskSetBuilderInterface
    {
        return $this->createMock(TaskFromYamlTaskSetBuilderInterface::class);
    }
}
