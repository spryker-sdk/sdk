<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\Setting\ProjectSettingsInitializer;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectFilesInitializer;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group Setting
 * @group ProjectSettingsInitializer
 * @group ProjectFilesInitializerTest
 */
class ProjectFilesInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitProjectFilesShouldCreateProjectSettingsFiles(): void
    {
        // Arrange
        $projectSettingsDir = vfsStream::setup('.ssdk');
        $projectSettingsDir->addChild(new vfsStreamFile('settings'));
        $projectSettingsFile = vfsStream::url('.ssdk') . '/settings';

        $fileInitializer = new ProjectFilesInitializer($projectSettingsFile);

        // Act
        $fileInitializer->initProjectFiles();

        // Assert
        $this->assertTrue($projectSettingsDir->hasChild('.gitignore'));
    }

    /**
     * @return void
     */
    public function testIsProjectSettingsInitialisedShouldReturnProjectInitializedWhenSettingFileExists(): void
    {
        // Arrange
        $projectSettingsDir = vfsStream::setup('.ssdk');
        $projectSettingsDir->addChild(new vfsStreamFile('settings'));
        $projectSettingsFile = vfsStream::url('.ssdk') . '/settings';

        $fileInitializer = new ProjectFilesInitializer($projectSettingsFile);

        // Act
        $isInitialized = $fileInitializer->isProjectSettingsInitialised();

        // Assert
        $this->assertTrue($isInitialized);
    }
}
