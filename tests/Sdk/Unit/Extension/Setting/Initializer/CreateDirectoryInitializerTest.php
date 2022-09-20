<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Setting\Initializer;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Extension\Setting\Initializer\CreateDirectoryInitializer;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

/**
 * @group Sdk
 * @group Extension
 * @group Setting
 * @group Initializer
 * @group CreateDirectoryInitializerTest
 */
class CreateDirectoryInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateDir(): void
    {
        //Arrange
        $vfsStream = vfsStream::setup('baseDir');
        $vfsStream->addChild(new vfsStreamDirectory('settingDir'));

        $pathDirPath = vfsStream::url('baseDir') . '/settingDir';

        $createDirectoryInitializer = new CreateDirectoryInitializer();
        $setting = $this->createSettingInterfaceMock($pathDirPath);

        //Act
        $createDirectoryInitializer->initialize($setting);

        //Assert
        $this->assertTrue($vfsStream->hasChild('settingDir'));
    }

    /**
     * @return void
     */
    public function testCreateDirWhenItDoesNotExist(): void
    {
        //Arrange
        $vfsStream = vfsStream::setup('baseDir');
        $pathDirPath = vfsStream::url('baseDir') . '/settingDir';
        $createDirectoryInitializer = new CreateDirectoryInitializer();
        $setting = $this->createSettingInterfaceMock($pathDirPath);

        //Act
        $createDirectoryInitializer->initialize($setting);

        //Assert
        $this->assertTrue($vfsStream->hasChild('settingDir'));
    }

    /**
     * @param string $fsPathToCreate
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingInterfaceMock(string $fsPathToCreate): SettingInterface
    {
        $settingInterfaceMock = $this->createMock(SettingInterface::class);
        $settingInterfaceMock->method('getValues')->willReturn($fsPathToCreate);

        return $settingInterfaceMock;
    }
}
