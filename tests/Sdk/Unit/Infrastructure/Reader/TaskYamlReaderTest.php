<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Reader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Reader
 * @group TaskYamlReaderTest
 * Add your own group annotations below this line
 */
class TaskYamlReaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader
     */
    protected TaskYamlReader $reader;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->settingRepository = $this->createMock(SettingRepository::class);

        $this->reader = new TaskYamlReader(
            $this->settingRepository,
            new Finder(),
            new Yaml(),
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testFindAllWithoutDefinedExtensionDirsSettingShouldThrowException(): void
    {
        // Arrange
        $exception = new MissingSettingException('Setting by path "extension_dirs" not found');

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('extension_dirs')
            ->willThrowException($exception);

        $this->expectException(MissingSettingException::class);
        $this->expectExceptionMessage($exception->getMessage());

        // Act
        $this->reader->readFiles();
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
            ->method('getOneByPath')
            ->with('extension_dirs')
            ->willReturn($setting);

        $taskMock = $this->createMock(Task::class);
        $taskMock->expects($this->any())
            ->method('getId')
            ->will($this->returnCallback(function () {
                return 'test:task:' . (mt_rand(100, 99999));
            }));

        // Act
        $result = $this->reader->readFiles();

        // Assert
        $this->assertCount(2, $result->getTasks());
        $this->assertCount(1, $result->getTaskSets());
    }
}
