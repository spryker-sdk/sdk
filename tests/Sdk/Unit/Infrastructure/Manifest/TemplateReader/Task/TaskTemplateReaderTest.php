<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\TemplateReader\Task;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Shared\FormatReader\TemplateFormatReaderInterface;
use SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Task\TaskTemplateReader;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group TemplateReader
 * @group Task
 * @group TaskTemplateReaderTest
 */
class TaskTemplateReaderTest extends Unit
{
    /**
     * @return void
     */
    public function testReadTemplateShouldThrowExceptionWhenFormatNotFound(): void
    {
        // Arrange
        $taskTemplateReader = new TaskTemplateReader([
            $this->createTemplateFormatReaderMock('php', false),
        ]);
        $manifestFile = new ManifestFile('yaml', '');
        $requestDto = $this->createManifestRequestDto($manifestFile);

        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskTemplateReader->readTemplate($requestDto);
    }

    /**
     * @return void
     */
    public function testReadTemplateShouldReadTemplate(): void
    {
        // Arrange
        $taskTemplateReader = new TaskTemplateReader([
            $this->createTemplateFormatReaderMock('php', true),
        ]);
        $manifestFile = new ManifestFile('php', '');
        $requestDto = $this->createManifestRequestDto($manifestFile);

        // Act
        $taskTemplateReader->readTemplate($requestDto);
    }

    /**
     * @param string $format
     * @param bool $shouldCallRead
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Shared\FormatReader\TemplateFormatReaderInterface
     */
    protected function createTemplateFormatReaderMock(string $format, bool $shouldCallRead): TemplateFormatReaderInterface
    {
        $readerMock = $this->createMock(TemplateFormatReaderInterface::class);
        $readerMock->method('getAcceptableFormat')->willReturn($format);

        if ($shouldCallRead) {
            $readerMock->expects($this->once())->method('readTemplate');
        }

        return $readerMock;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface
     */
    protected function createManifestRequestDto(ManifestFile $manifestFile): ManifestRequestDtoInterface
    {
        $manifestRequestDtoInterface = $this->createMock(ManifestRequestDtoInterface::class);
        $manifestRequestDtoInterface
            ->method('getManifestFile')
            ->willReturn($manifestFile);

        return $manifestRequestDtoInterface;
    }
}
