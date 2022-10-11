<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\ManifestWriter\Task;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task\PhpTaskManifestWriter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group ManifestWriter
 * @group Task
 * @group PhpTaskManifestWriterTest
 */
class PhpTaskManifestWriterTest extends Unit
{
    /**
     * @return void
     */
    public function testWriteShouldWriteFileContent(): void
    {
        // Arrange
        $writer = new PhpTaskManifestWriter($this->createFilesystemMock(), 'test_dir');
        $manifestFile = new ManifestFile('php', 'task');

        // Act
        $filePath = $writer->write('', $manifestFile);

        // Assert
        $this->assertSame('test_dir/Task.php', $filePath);
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function createFilesystemMock(): Filesystem
    {
        $fileSystemMock = $this->createMock(Filesystem::class);
        $fileSystemMock->expects($this->once())->method('dumpFile');

        return $fileSystemMock;
    }
}
