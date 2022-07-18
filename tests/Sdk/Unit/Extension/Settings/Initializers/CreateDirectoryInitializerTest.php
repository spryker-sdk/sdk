<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Extension\Settings\Initializers;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\Sdk\Extension\Settings\Initializers\CreateDirectoryInitializer;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class CreateDirectoryInitializerTest extends Unit
{
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
