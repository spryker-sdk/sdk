<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Core\Appplication\Service\FileManager;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group FileManagerTest
 */
class FileManagerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\FileManager
     */
    protected FileManager $fileManager;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected vfsStreamDirectory $vfsStream;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fileManager = new FileManager();
        $this->vfsStream = vfsStream::setup();
    }

    /**
     * @dataProvider provideFileData
     *
     * @param string $fileName
     * @param string $content
     *
     * @return void
     */
    public function testCreateShouldCreateFileByPathWithContent(string $fileName, string $content): void
    {
        // Arrange
        $path = $this->vfsStream->url() . '/' . $fileName;
        $file = $this->tester->createFile($path, $content);

        // Act
        $result = $this->fileManager->create($file);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($this->vfsStream->hasChild($fileName));
        $this->assertSame($content, $this->vfsStream->getChild($fileName)->getContent());
    }

    /**
     * @dataProvider provideFileData
     *
     * @param string $fileName
     * @param string $content
     *
     * @return void
     */
    public function testRemoveExistedFileShouldReturnTrue(string $fileName, string $content): void
    {
        // Arrange
        $path = $this->vfsStream->url() . '/' . $fileName;
        $file = $this->tester->createFile($path, $content);

        $vfsFile = $this->tester->createVfsStreamFile($fileName, $content);
        $this->vfsStream->addChild($vfsFile);

        // Act
        $result = $this->fileManager->remove($file);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($this->vfsStream->hasChild($fileName));
    }

    /**
     * @dataProvider provideFileData
     *
     * @param string $fileName
     * @param string $content
     *
     * @return void
     */
    public function testRemoveNotExistedFileShouldNotRemoveFileAndShouldReturnFalse(string $fileName, string $content): void
    {
        // Arrange
        $path = $this->vfsStream->url() . '/' . $fileName;
        $file = $this->tester->createFile($path, $content);

        // Act
        $result = $this->fileManager->remove($file);

        // Assert
        $this->assertFalse($this->vfsStream->hasChild($fileName));
        $this->assertFalse($result);
    }

    /**
     * @return array<array<string>>
     */
    public function provideFileData(): array
    {
        return [
            ['data.txt', 'Test data'],
        ];
    }
}
