<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Extension\Setting\Initializer;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Extension\Setting\Initializer\SdkUuidInitializer;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Extension
 * @group Setting
 * @group Initializer
 * @group SdkUuidInitializerTest
 * Add your own group annotations below this line
 */
class SdkUuidInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitializeWhenSettingNotFound(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $settingRepository = $this->createSettingRepositoryMock(true);
        $sdkUuidInitializer = new SdkUuidInitializer($settingRepository);

        //Act
        $sdkUuidInitializer->initialize($setting);

        //Assert
        $this->assertRegExp('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $setting->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeWhenSettingValueIsEmpty(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $existingSetting = new Setting('soma_path', '');
        $settingRepository = $this->createSettingRepositoryMock(true, $existingSetting);
        $sdkUuidInitializer = new SdkUuidInitializer($settingRepository);

        //Act
        $sdkUuidInitializer->initialize($setting);

        //Assert
        $this->assertRegExp('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $setting->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeWhenSettingExistWithValue(): void
    {
        //Arrange
        $value = '8857b049-d595-4b41-8a26-95693217e932';
        $setting = new Setting('soma_path', '');
        $existingSetting = new Setting('soma_path', $value);
        $settingRepository = $this->createSettingRepositoryMock(false, $existingSetting);
        $sdkUuidInitializer = new SdkUuidInitializer($settingRepository);

        //Act
        $sdkUuidInitializer->initialize($setting);

        //Assert
        $this->assertEquals($value, $setting->getValues());
    }

    /**
     * @param bool $shouldBeSaved
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface|null $setting
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository
     */
    public function createSettingRepositoryMock(bool $shouldBeSaved, ?SettingInterface $setting = null): SettingRepositoryInterface
    {
        $settingRepository = $this->createMock(SettingRepository::class);
        $settingRepository->method('findOneByPath')->willReturn($setting);

        $shouldBeSaved
            ? $settingRepository->expects($this->once())->method('save')
            : $settingRepository->expects($this->never())->method('save');

        return $settingRepository;
    }
}
