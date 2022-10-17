<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Repository;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Service\PathResolver;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class ProjectSettingRepositoryTest extends Unit
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $coreSettingRepository;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository
     */
    protected ProjectSettingRepository $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected vfsStreamDirectory $vfsStream;

    /**
     * @var string
     */
    protected string $projectSettingFileName = '';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->coreSettingRepository = $this->createMock(SettingRepositoryInterface::class);
        $this->yamlParser = $this->createMock(Yaml::class);
        $this->pathResolver = $this->createMock(PathResolver::class);
        $this->vfsStream = vfsStream::setup();

        $this->projectSettingRepository = new ProjectSettingRepository(
            $this->container,
            $this->coreSettingRepository,
            new Yaml(),
            $this->projectSettingFileName,
            $this->pathResolver,
        );
    }

    /**
     * @return void
     */
    public function testFindOneByPathShouldReturnSetting(): void
    {
        // Arrange
        $settingPath = 'setting_path';

        $setting = $this->tester->createInfrastructureSetting(
            $settingPath,
            '/root/test/path',
            1,
            SettingInterface::STRATEGY_REPLACE,
            'path',
        );

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($settingPath)
            ->willReturn($setting);

        // Act
        $result = $this->projectSettingRepository->findOneByPath($settingPath);

        // Assert
        $this->assertSame($setting, $result);
    }

    /**
     * @return void
     */
    public function testFindOneByPathWhenNotFoundShouldReturnNull(): void
    {
        // Arrange
        $settingPath = 'setting_path';

        $setting = null;

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($settingPath)
            ->willReturn($setting);

        // Act
        $result = $this->projectSettingRepository->findOneByPath($settingPath);

        // Assert
        $this->assertSame($setting, $result);
    }

    /**
     * @return void
     */
    public function testFindOneByPathWithCoreSettingShouldThrowException(): void
    {
        // Arrange
        $settingPath = 'setting_path';

        $setting = $this->tester->createSetting($settingPath, '/root/test/path');

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($settingPath)
            ->willReturn($setting);

        $this->expectException(InvalidTypeException::class);

        // Act
        $this->projectSettingRepository->findOneByPath($settingPath);
    }

    /**
     * @return void
     */
    public function testGetOneByPathShouldReturnSetting(): void
    {
        // Arrange
        $settingPath = 'setting_path';

        $setting = $this->tester->createInfrastructureSetting(
            $settingPath,
            '/root/test/path',
            1,
            SettingInterface::STRATEGY_REPLACE,
            'path',
        );

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($settingPath)
            ->willReturn($setting);

        // Act
        $result = $this->projectSettingRepository->getOneByPath($settingPath);

        // Assert
        $this->assertSame($setting, $result);
    }

    /**
     * @return void
     */
    public function testGetOneByPathWhenNotFoundShouldReturnNull(): void
    {
        // Arrange
        $settingPath = 'setting_path';

        $setting = null;

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($settingPath)
            ->willReturn($setting);

        $this->expectException(MissingSettingException::class);

        // Act
        $this->projectSettingRepository->getOneByPath($settingPath);
    }

    /**
     * @return void
     */
    public function testFindCoreSettingsShouldReturnSettings(): void
    {
        // Arrange
        $settings = [
            $this->tester->createSetting('path1', 'value1'),
            $this->tester->createSetting('path2', 'value2'),
        ];

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findCoreSettings')
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findCoreSettings();

        // Assert
        $this->assertSame($settings, $result);
    }

    /**
     * @return void
     */
    public function testSaveShouldSaveSharedSetting(): void
    {
        // Arrange
        $sharedSetting = $this->tester->createSetting('pathShared', 'valueShared', SettingInterface::STRATEGY_REPLACE, Setting::SETTING_TYPE_LOCAL);

        $this->projectSettingFileName = $this->vfsStream->url() . '/settings';

        $this->projectSettingRepository = new ProjectSettingRepository(
            $this->container,
            $this->coreSettingRepository,
            new Yaml(),
            $this->projectSettingFileName,
            $this->pathResolver,
        );

        // Act
        $sharedResult = $this->projectSettingRepository->save($sharedSetting);

        // Assert
        $this->assertSame($sharedSetting, $sharedResult);

        $this->assertNotEmpty($this->vfsStream->getChildren());
        $this->assertSame("pathShared: valueShared\n", $this->vfsStream->getChild('settings.local')->getContent());
    }

    /**
     * @return void
     */
    public function testSaveShouldSaveSetting(): void
    {
        // Arrange
        $setting = $this->tester->createSetting('path', 'value');

        $this->projectSettingFileName = $this->vfsStream->url() . '/settings';

        $this->projectSettingRepository = new ProjectSettingRepository(
            $this->container,
            $this->coreSettingRepository,
            new Yaml(),
            $this->projectSettingFileName,
            $this->pathResolver,
        );

        // Act
        $result = $this->projectSettingRepository->save($setting);

        // Assert
        $this->assertSame($setting, $result);

        $this->assertNotEmpty($this->vfsStream->getChildren());
        $this->assertSame("path: value\n", $this->vfsStream->getChild('settings.local')->getContent());
    }

    /**
     * @return void
     */
    public function testSaveMultipleShouldSaveSetting(): void
    {
        // Arrange
        $settings = [
            $this->tester->createSetting('path1', 'value1'),
            $this->tester->createSetting('path2', 'value2'),
        ];

        $this->projectSettingFileName = $this->vfsStream->url() . '/settings';

        $this->projectSettingRepository = new ProjectSettingRepository(
            $this->container,
            $this->coreSettingRepository,
            new Yaml(),
            $this->projectSettingFileName,
            $this->pathResolver,
        );

        // Act
        $result = $this->projectSettingRepository->saveMultiple($settings);

        // Assert
        $this->assertSame($settings, $result);
        $this->assertNotEmpty($this->vfsStream->getChildren());
        $this->assertSame("path1: value1\npath2: value2\n", $this->vfsStream->getChild('settings.local')->getContent());
    }

    /**
     * @return void
     */
    public function testFindProjectSettingsWithEmptySettingsShouldReturnEmptyArray(): void
    {
        // Arrange
        $settings = [];

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findProjectSettings')
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findProjectSettings();

        // Assert
        $this->assertSame($settings, $result);
    }

    /**
     * @return void
     */
    public function testFindProjectSettingsShouldReturnSettings(): void
    {
        // Arrange
        $settings = [
            $this->tester->createInfrastructureSetting('path1', 'value1'),
            $this->tester->createInfrastructureSetting('path2', 'value2'),
            $this->tester->createInfrastructureSetting('path3', 'value3'),
        ];

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findProjectSettings')
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findProjectSettings();

        // Assert
        $this->assertSame($settings, $result);
    }

    /**
     * @return void
     */
    public function testFindWithEmptySettingsShouldReturnEmptyArray(): void
    {
        // Arrange
        $settings = [];

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findProjectSettings')
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findProjectSettings();

        // Assert
        $this->assertSame($settings, $result);
    }

    /**
     * @return void
     */
    public function testFindShouldReturnSettings(): void
    {
        // Arrange
        $settings = [
            $this->tester->createInfrastructureSetting('path1', ['key1' => '/root/path1', 'key2' => '/root/path2'], 1, SettingInterface::STRATEGY_REPLACE, 'path', false),
            $this->tester->createInfrastructureSetting('path2', 'value2'),
            $this->tester->createInfrastructureSetting('path3', 'value3'),
        ];

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findProjectSettings')
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findProjectSettings();

        // Assert
        $this->assertSame($settings, $result);
    }

    /**
     * @group testFindByPathsShouldReturnSettingsByPaths
     *
     * @return void
     */
    public function testFindByPathsShouldReturnSettingsByPaths(): void
    {
        // Arrange
        $paths = ['path1', 'path2', 'path3'];

        $settings = [
            $this->tester->createInfrastructureSetting('path1', ['key1' => '/root/path1', 'key2' => '/root/path2'], 1, SettingInterface::STRATEGY_REPLACE, 'path', false),
            $this->tester->createInfrastructureSetting('path2', 'value2'),
            $this->tester->createInfrastructureSetting('path3', 'value3'),
        ];

        $settingsFile = $this->tester->createVfsStreamFile(
            'settings',
            <<<YAML
path1:
    - /root/path1
    - /root/path2
path2: value2
path3: value3
YAML,
        );
        $this->vfsStream->addChild($settingsFile);

        $this->projectSettingFileName = $settingsFile->url();

        $this->projectSettingRepository = new ProjectSettingRepository(
            $this->container,
            $this->coreSettingRepository,
            new Yaml(),
            $this->projectSettingFileName,
            $this->pathResolver,
        );

        $this->coreSettingRepository
            ->expects($this->once())
            ->method('findByPaths')
            ->with($paths)
            ->willReturn($settings);

        // Act
        $result = $this->projectSettingRepository->findByPaths($paths);

        // Assert
        $this->assertSame($settings, $result);
    }
}
