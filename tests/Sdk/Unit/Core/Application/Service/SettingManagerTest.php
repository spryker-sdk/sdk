<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Service\SettingManager;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group SettingManagerTest
 */
class SettingManagerTest extends Unit
{
    /**
     * @var string
     */
    protected const SETTINGS = 'settings';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const VALUE = 'value';

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\SettingManager
     */
    protected SettingManager $settingManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepositoryMock;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected SettingRepositoryInterface $settingRepositoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $this->settingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);

        $this->settingManager = new SettingManager(
            $this->projectSettingRepositoryMock,
            $this->settingRepositoryMock,
        );
    }

    /**
     * @dataProvider provideSettingList
     *
     * @param mixed $pathValues
     *
     * @return void
     */
    public function testProjectSetSettings(array $pathValues): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('saveMultiple')
            ->willReturnCallback(function (array $settings) {
                return $settings;
            });

        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findByPaths')
            ->willReturnCallback(function (array $settingKeys) use ($pathValues): array {
                $settings = [];
                foreach (array_intersect_key($pathValues, array_flip($settingKeys)) as $path => $value) {
                    $settings[] = $this->tester->createSetting($path, $value);
                }

                return $settings;
            });

        // Act
        $settings = $this->settingManager->setSettings($pathValues);

        // Assert
        $this->assertCount(count($pathValues), $settings);
    }

    /**
     * @dataProvider provideSettingList
     *
     * @param mixed $pathValues
     *
     * @return void
     */
    public function testSetSettings(array $pathValues): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findByPaths')
            ->willReturnCallback(function (array $settingKeys) use ($pathValues): array {
                $settings = [];
                foreach (array_intersect_key($pathValues, array_flip($settingKeys)) as $path => $value) {
                    $settings[] = $this->tester->createSetting($path, $value, SettingInterface::STRATEGY_REPLACE, false);
                }

                return $settings;
            });

        $this->settingRepositoryMock
            ->expects($this->once())
            ->method('saveMultiple')
            ->willReturnCallback(function (array $settings) {
                return $settings;
            });

        // Act
        $settings = $this->settingManager->setSettings($pathValues);

        // Assert
        $this->assertCount(count($pathValues), $settings);
    }

    /**
     * @dataProvider provideSettings
     *
     * @param string $path
     * @param mixed $value
     *
     * @return void
     */
    public function testProjectSetSetting(string $path, $value): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findOneByPath')
            ->willReturnCallback(function (string $settingPath) use ($value): SettingInterface {
                return $this->tester->createSetting($settingPath, $value);
            });

        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (SettingInterface $setting) {
                return $setting;
            });

        // Act
        $setting = $this->settingManager->setSetting($path, $value);

        // Assert
        $this->assertSame($path, $setting->getPath());
        $this->assertSame($value, $setting->getValues());
    }

    /**
     * @dataProvider provideSettings
     *
     * @param string $path
     * @param mixed $value
     *
     * @return void
     */
    public function testSetSettingWithStrategyReplace(string $path, $value): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findOneByPath')
            ->willReturnCallback(function (string $settingPath) use ($value): SettingInterface {
                return $this->tester->createSetting($settingPath, $value, SettingInterface::STRATEGY_REPLACE, false);
            });

        $this->settingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (SettingInterface $setting) {
                return $setting;
            });

        // Act
        $setting = $this->settingManager->setSetting($path, $value);

        // Assert
        $this->assertSame($path, $setting->getPath());
        $this->assertSame($value, $setting->getValues());
    }

    /**
     * @dataProvider provideSettingsForMergeStrategy
     *
     * @param string $path
     * @param mixed $value
     *
     * @return void
     */
    public function testSetSettingWithStrategyMerge(string $path, $value): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($path)
            ->willReturnCallback(function (string $settingPath) use ($value): SettingInterface {
                return $this->tester->createSetting($settingPath, $value, SettingInterface::STRATEGY_MERGE, false);
            });

        $this->settingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (SettingInterface $setting) {
                return $setting;
            });

        // Act
        $setting = $this->settingManager->setSetting($path, $value);

        // Assert
        $this->assertSame($path, $setting->getPath());
        $this->assertSame($value, $setting->getValues());
    }

    /**
     * @dataProvider provideSettings
     *
     * @param string $path
     * @param mixed $value
     *
     * @return void
     */
    public function testSetSettingShouldThrowExceptionWhenSettingNotFound(string $path, $value): void
    {
        // Arrange
        $this->projectSettingRepositoryMock
            ->expects($this->once())
            ->method('findOneByPath')
            ->with($path)
            ->willReturn(null);

        $this->expectException(MissingSettingException::class);
        $this->expectExceptionMessage('No setting definition for ' . $path . ' found');

        // Act
        $this->settingManager->setSetting($path, $value);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function provideSettingList(): array
    {
        return [
            [
                static::SETTINGS => [
                    'path1' => false,
                    'path2' => 'string',
                    'path3' => ['string'],
                ],
            ],
        ];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function provideSettings(): array
    {
        return [
            [
                static::PATH => 'path1',
                static::VALUE => true,
            ], [
                static::PATH => 'path2',
                static::VALUE => 'string',
            ], [
                static::PATH => 'path3',
                static::VALUE => ['string'],
            ],
        ];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function provideSettingsForMergeStrategy(): array
    {
        return [
            [
                static::PATH => 'path2',
                static::VALUE => ['string'],
            ],
            [
                static::PATH => 'path3',
                static::VALUE => ['string'],
            ],
        ];
    }
}
