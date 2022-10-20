<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\ManifestWriter\Task;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\ManifestFormatWriterInterface;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task\TaskManifestWriter;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group ManifestWriter
 * @group Task
 * @group TaskManifestWriterTest
 */
class TaskManifestWriterTest extends Unit
{
    /**
     * @return void
     */
    public function testWriteShouldThrowExceptionWhenWriterNotFound(): void
    {
        // Arrange
        $taskManifestWriter = new TaskManifestWriter([
            $this->createManifestFormatWriterMock('php', false),
        ]);

        $manifestFile = new ManifestFile('yaml', '');

        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskManifestWriter->write('', $manifestFile);
    }

    /**
     * @return void
     */
    public function testWriteShouldWriteContent(): void
    {
        // Arrange
        $taskManifestWriter = new TaskManifestWriter([
            $this->createManifestFormatWriterMock('php', true),
        ]);

        $manifestFile = new ManifestFile('php', '');

        // Act
        $taskManifestWriter->write('', $manifestFile);
    }

    /**
     * @param string $format
     * @param bool $shouldCallWrite
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\ManifestFormatWriterInterface
     */
    protected function createManifestFormatWriterMock(string $format, bool $shouldCallWrite): ManifestFormatWriterInterface
    {
        $writerMock = $this->createMock(ManifestFormatWriterInterface::class);
        $writerMock->method('getAcceptableFormat')->willReturn($format);

        if ($shouldCallWrite) {
            $writerMock->expects($this->once())->method('write');
        }

        return $writerMock;
    }
}
