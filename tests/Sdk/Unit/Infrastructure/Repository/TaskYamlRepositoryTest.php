<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Repository;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskYamlRepository;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Enum\Setting;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepositoryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\TaskYamlRepository
     */
    protected TaskYamlRepository $taskYamlRepository;

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

        $this->settingRepository = $this->createMock(SettingRepository::class);
        $this->fileFinder = $this->createMock(Finder::class);
        $this->taskYamlRepository = new TaskYamlRepository(
            $this->settingRepository,
            new Finder(),
            new Yaml(),
            $this->createTaskFromYamlTaskSetBuilderMock(),
            $this->createManifestValidationBuilderMock(),
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
            ->with(Setting::PATH_EXTENSION_DIRS)
            ->willReturn(null);

        $this->expectException(MissingSettingException::class);
        $this->expectExceptionMessage(sprintf('%s are not configured properly', Setting::PATH_EXTENSION_DIRS));

        // Act
        $this->taskYamlRepository->findAll();
    }

    /**
     * @return void
     */
    public function testFindAllShouldReturnBuiltTasks(): void
    {
        // Arrange
        $pathToTasks = realpath(__DIR__ . '/../../../../_support/data/');

        $setting = $this->tester->createInfrastructureSetting(
            Setting::PATH_EXTENSION_DIRS,
            [$pathToTasks],
        );

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with(Setting::PATH_EXTENSION_DIRS)
            ->willReturn($setting);

        // Act
        $result = $this->taskYamlRepository->findAll();

        // Assert
        $this->assertCount(4, $result);
    }

    /**
     * @return void
     */
    public function testIsTaskIdExist(): void
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
        $result = $this->taskYamlRepository->isTaskIdExist('hello:world');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testGetTaskPlaceholders(): void
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
        $result = $this->taskYamlRepository->getTaskPlaceholders(['hello:world']);

        // Assert
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result['hello:world']);
    }

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTask(): void
    {
        // Arrange
        $pathToTasks = realpath(__DIR__ . '/../../../../_support/data/');

        $setting = $this->tester->createInfrastructureSetting(
            Setting::PATH_EXTENSION_DIRS,
            [$pathToTasks],
        );

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with(Setting::PATH_EXTENSION_DIRS)
            ->willReturn($setting);

        // Act
        $result = $this->taskYamlRepository->findById('hello:world');

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
            Setting::PATH_EXTENSION_DIRS,
            [$pathToTasks],
        );

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with(Setting::PATH_EXTENSION_DIRS)
            ->willReturn($setting);

        // Act
        $result = $this->taskYamlRepository->findById('not exist');

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

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    public function createManifestValidationBuilderMock(): ManifestValidatorInterface
    {
        $manifestValidation = $this->createMock(ManifestValidatorInterface::class);
        $manifestValidation
            ->method('validate')
            ->will($this->returnArgument(1));

        return $manifestValidation;
    }
}
