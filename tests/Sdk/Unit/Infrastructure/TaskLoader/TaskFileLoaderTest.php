<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\TaskLoader;

use Codeception\Test\Unit;
use Hello\Task\HelloStagedTaskSet;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\TaskBuilder;
use SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\TaskLoader\TaskFileLoader;
use SprykerSdk\Sdk\Infrastructure\TaskReader\TaskFileReader;
use SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group TaskLoader
 * @group TaskFileLoaderTest
 */
class TaskFileLoaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\TaskLoader\TaskFileLoader
     */
    protected TaskFileLoader $taskFileLoader;

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
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $taskSetWithStringReference = $this->createMock(TaskSetInterface::class);
        $taskSetWithStringReference->method('getId')->willReturn('test:task:set');
        $taskSetWithStringReference->method('getSubTasks')->willReturn(['violation:php:clean-report-dir']);

        $taskRegistry = new TaskRegistry([
            new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class)),
            new HelloStagedTaskSet(),
            $taskSetWithStringReference,
        ]);
        $placeholderBuilder = new PlaceholderBuilder($taskRegistry, new NestedTaskSetValidator(), new PlaceholderFactory());
        $taskBuilder = new TaskBuilder(
            $placeholderBuilder,
            new CommandBuilder($taskRegistry, new ConverterBuilder(), new NestedTaskSetValidator(), new CommandFactory()),
            new LifecycleBuilder(
                new LifecycleEventDataBuilder(
                    new FileCollectionBuilder(),
                    new LifecycleCommandBuilder(),
                    $placeholderBuilder,
                ),
            ),
            $taskRegistry,
        );

        $this->settingRepository = $this->createMock(SettingRepository::class);
        $this->fileFinder = $this->createMock(Finder::class);
        $this->taskFileLoader = new TaskFileLoader(
            $this->settingRepository,
            $taskBuilder,
            $this->createTaskFromYamlTaskSetBuilderMock(),
            $taskRegistry,
            new TaskFileReader(new Finder(), new Yaml()),
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
        $this->taskFileLoader->loadAll();
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

        // Act
        $result = $this->taskFileLoader->loadAll();

        // Assert
        $this->assertCount(6, $result);
    }

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTask(): void
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

        // Act
        $result = $this->taskFileLoader->loadOneById('hello:world');

        // Assert
        $this->assertSame('hello:world', $result->getId());
        $this->assertSame('hello:php', $result->getSuccessor());
        $this->assertFalse($result->isDeprecated());
        $this->assertSame('1.0.0', $result->getVersion());
    }

    /**
     * @return void
     */
    public function testFindByNotExistedIdShouldReturnNull(): void
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

        // Act
        $result = $this->taskFileLoader->loadOneById('not exist');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    public function createTaskFromYamlTaskSetBuilderMock(): TaskFromYamlTaskSetBuilderInterface
    {
        return $this->createMock(TaskFromYamlTaskSetBuilderInterface::class);
    }
}
