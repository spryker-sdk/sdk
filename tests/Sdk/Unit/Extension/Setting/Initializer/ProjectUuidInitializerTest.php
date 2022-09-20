<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Setting\Initializer;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Extension\Setting\Initializer\ProjectUuidInitializer;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

/**
 * @group Sdk
 * @group Extension
 * @group Setting
 * @group Initializer
 * @group ProjectUuidInitializerTest
 */
class ProjectUuidInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitializeWhenSettingNotFound(): void
    {
        //Arrange
        $setting = $this->createSettingMock('');
        $setting->expects($this->once())->method('setValues')->with($this->matchesRegularExpression('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/'));

        $projectSettingRepository = $this->createProjectSettingRepositoryMock(null);
        $projectSettingRepository->expects($this->once())->method('save');

        $projectUuidInitializer = new ProjectUuidInitializer($projectSettingRepository);

        //Act
        $projectUuidInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testInitializeWhenSettingValueIsEmpty(): void
    {
        //Arrange
        $setting = $this->createSettingMock('');
        $setting->expects($this->once())->method('setValues')->with($this->matchesRegularExpression('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/'));

        $projectSettingRepository = $this->createProjectSettingRepositoryMock($setting);
        $projectSettingRepository->expects($this->once())->method('save');

        $projectUuidInitializer = new ProjectUuidInitializer($projectSettingRepository);

        //Act
        $projectUuidInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testInitializeWhenSettingExist(): void
    {
        //Arrange
        $setting = $this->createSettingMock('someValue');
        $setting->expects($this->once())->method('setValues')->with($this->equalTo('someValue'));

        $projectSettingRepository = $this->createProjectSettingRepositoryMock($setting);
        $projectUuidInitializer = new ProjectUuidInitializer($projectSettingRepository);

        //Act
        $projectUuidInitializer->initialize($setting);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface|null $returnSetting
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProjectSettingRepositoryMock(?SettingInterface $returnSetting): ProjectSettingRepositoryInterface
    {
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->method('findOneByPath')->willReturn($returnSetting);

        return $projectSettingRepositoryMock;
    }

    /**
     * @param string|null $returnValue
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSettingMock(?string $returnValue): SettingInterface
    {
        $settingMock = $this->createMock(SettingInterface::class);
        $settingMock->method('getValues')->willReturn($returnValue);

        return $settingMock;
    }
}
