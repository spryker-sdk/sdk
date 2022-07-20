<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Repository;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Extension\Tasks\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskYamlRepository;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlRepositoryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\TaskYamlRepository
     */
    protected TaskYamlRepository $taskYamlRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
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
        $this->taskYamlRepository->findAll();
    }

    /**
     * @return void
     */
    public function testFindAllShouldReturnBuiltTasks(): void
    {
        // Arrange
        $pathToTasks = realpath(__DIR__ . '/../../../../_support');

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
        $result = $this->taskYamlRepository->findAll();

        // Assert
        $this->assertCount(4, $result);
    }

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTask(): void
    {
        // Arrange
        $pathToTasks = realpath(__DIR__ . '/../../../../_support');

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
        $pathToTasks = realpath(__DIR__ . '/../../../../_support');

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
        $result = $this->taskYamlRepository->findById('not exist');

        // Assert
        $this->assertNull($result);
    }
}
