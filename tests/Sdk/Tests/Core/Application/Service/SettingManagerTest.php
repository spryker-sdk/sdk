<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\SettingManager;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;

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
     * @dataProvider provideSettingList
     *
     * @param mixed $pathValues
     *
     * @return void
     */
    public function testProjectSetSettings(array $pathValues): void
    {
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->expects($this->once())
            ->method('saveMultiple')
            ->willReturnCallback(function (array $settings) {
                return $settings;
            });
        $projectSettingRepositoryMock->expects($this->once())
            ->method('findByPaths')
            ->willReturnCallback(function (array $settingKeys) use ($pathValues): array {
                $settings = [];
                foreach (array_intersect_key($pathValues, array_flip($settingKeys)) as $path => $value) {
                    $settings[] = new Setting(
                        $path,
                        $value ?? null,
                        SettingInterface::STRATEGY_REPLACE,
                        gettype($value),
                    );
                }

                return $settings;
            });

        $settingManager = new SettingManager(
            $projectSettingRepositoryMock,
            $this->createMock(ProjectSettingRepositoryInterface::class),
        );

        $settings = $settingManager->setSettings($pathValues);

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
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->expects($this->once())
            ->method('findByPaths')
            ->willReturnCallback(function (array $settingKeys) use ($pathValues): array {
                $settings = [];
                foreach (array_intersect_key($pathValues, array_flip($settingKeys)) as $path => $value) {
                    $settings[] = new Setting(
                        $path,
                        $value ?? null,
                        SettingInterface::STRATEGY_REPLACE,
                        gettype($value),
                        false,
                    );
                }

                return $settings;
            });
        $settingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $settingRepositoryMock->expects($this->once())
            ->method('saveMultiple')
            ->willReturnCallback(function (array $settings) {
                return $settings;
            });

        $settingManager = new SettingManager(
            $projectSettingRepositoryMock,
            $settingRepositoryMock,
        );

        $settings = $settingManager->setSettings($pathValues);

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
    public function testProjectSetSetting(string $path, mixed $value): void
    {
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->expects($this->once())
            ->method('findOneByPath')
            ->willReturnCallback(function (string $settingPath) use ($value): SettingInterface {
                return new Setting(
                    $settingPath,
                    $value,
                    SettingInterface::STRATEGY_REPLACE,
                    gettype($value),
                );
            });
        $projectSettingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (SettingInterface $setting) {
                return $setting;
            });

        $settingManager = new SettingManager(
            $projectSettingRepositoryMock,
            $this->createMock(ProjectSettingRepositoryInterface::class),
        );
        $setting = $settingManager->setSetting($path, $value);

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
    public function testSetSetting(string $path, mixed $value): void
    {
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->expects($this->once())
            ->method('findOneByPath')
            ->willReturnCallback(function (string $settingPath) use ($value): SettingInterface {
                return new Setting(
                    $settingPath,
                    $value,
                    SettingInterface::STRATEGY_REPLACE,
                    gettype($value),
                    false,
                );
            });

        $settingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $settingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (SettingInterface $setting) {
                return $setting;
            });

        $settingManager = new SettingManager(
            $projectSettingRepositoryMock,
            $settingRepositoryMock,
        );
        $setting = $settingManager->setSetting($path, $value);

        $this->assertSame($path, $setting->getPath());
        $this->assertSame($value, $setting->getValues());
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
}
