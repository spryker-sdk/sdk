<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\ManifestWriter\Task\FormatWriter;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task\FormatWriter\YamlTaskManifestFormatWriter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group ManifestWriter
 * @group Task
 * @group FormatWriter
 * @group YamlTaskManifestFormatWriterTest
 * Add your own group annotations below this line
 */
class YamlTaskManifestFormatWriterTest extends Unit
{
    /**
     * @return void
     */
    public function testWriteShouldWriteFileContent(): void
    {
        // Arrange
        $writer = new YamlTaskManifestFormatWriter($this->createFilesystemMock(), 'test_dir');
        $manifestFile = new ManifestFile('yaml', 'task');

        // Act
        $filePath = $writer->write('', $manifestFile);

        // Assert
        $this->assertSame('test_dir/Task.yaml', $filePath);
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
