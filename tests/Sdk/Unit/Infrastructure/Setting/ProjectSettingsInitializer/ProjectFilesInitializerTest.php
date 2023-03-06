<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Setting\ProjectSettingsInitializer;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectFilesInitializer;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Setting
 * @group ProjectSettingsInitializer
 * @group ProjectFilesInitializerTest
 * Add your own group annotations below this line
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
        $projectSettingsDir->addChild(new vfsStreamFile('settings.local'));
        $projectSettingsFile = vfsStream::url('.ssdk') . '/settings';
        $localProjectSettingsFile = vfsStream::url('.ssdk') . '/settings.local';

        $fileInitializer = new ProjectFilesInitializer($projectSettingsFile, $localProjectSettingsFile, new Filesystem());

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
        $projectSettingsDir->addChild(new vfsStreamFile('settings.local'));
        $projectSettingsFile = vfsStream::url('.ssdk') . '/settings';
        $localProjectSettingsFile = vfsStream::url('.ssdk') . '/settings.local';

        $fileInitializer = new ProjectFilesInitializer($projectSettingsFile, $localProjectSettingsFile, new Filesystem());

        // Act
        $isInitialized = $fileInitializer->isProjectSettingsInitialised();

        // Assert
        $this->assertTrue($isInitialized);
    }
}
