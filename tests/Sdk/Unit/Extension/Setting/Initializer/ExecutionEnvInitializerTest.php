<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Extension\Setting\Initializer;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Extension\Setting\Initializer\ExecutionEnvInitializer;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\ExecutionEnv;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Extension
 * @group Setting
 * @group Initializer
 * @group ExecutionEnvInitializerTest
 * Add your own group annotations below this line
 */
class ExecutionEnvInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitializeSkipWhenExistingValueNotBlank(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $existingSetting = new Setting('soma_path', ExecutionEnv::DEVELOPER);
        $settingRepository = $this->createSettingRepositoryMock(false, $existingSetting);
        $initializer = new ExecutionEnvInitializer($settingRepository, true);

        //Act
        $initializer->initialize($setting);

        //Arrange
        $this->assertEquals(ExecutionEnv::DEVELOPER, $setting->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeSetValueWhenExistingSettingIsNull(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $settingRepository = $this->createSettingRepositoryMock(true);
        $initializer = new ExecutionEnvInitializer($settingRepository, true);

        //Act
        $initializer->initialize($setting);

        //Arrange
        $this->assertEquals(ExecutionEnv::CI, $setting->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeSetValueWhenExistingSettingIsEmptyAndCI(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $existingSetting = new Setting('soma_path', '');
        $settingRepository = $this->createSettingRepositoryMock(true, $existingSetting);
        $initializer = new ExecutionEnvInitializer($settingRepository, true);

        //Act
        $initializer->initialize($setting);

        //Arrange
        $this->assertEquals(ExecutionEnv::CI, $setting->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeSetValueWhenExistingSettingIsEmptyAndNonCI(): void
    {
        //Arrange
        $setting = new Setting('soma_path', '');
        $existingSetting = new Setting('soma_path', '');
        $settingRepository = $this->createSettingRepositoryMock(true, $existingSetting);
        $initializer = new ExecutionEnvInitializer($settingRepository, false);

        //Act
        $initializer->initialize($setting);

        //Arrange
        $this->assertEquals(ExecutionEnv::DEVELOPER, $setting->getValues());
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
