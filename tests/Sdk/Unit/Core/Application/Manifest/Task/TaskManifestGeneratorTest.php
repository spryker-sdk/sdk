<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Manifest\Task;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use SprykerSdk\Sdk\Core\Application\Manifest\Task\TaskManifestGenerator;

/**
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Manifest
 * @group Task
 * @group TaskManifestGeneratorTest
 */
class TaskManifestGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testGenerateShouldWriteTemplate(): void
    {
        // Arrange
        $manifestFile = new ManifestFile('yaml', '');
        $requestDto = $this->createManifestRequestDto($manifestFile);

        $taskManifestGenerator = new TaskManifestGenerator(
            $this->createManifestWriterMock('test/Task.yaml'),
            $this->createTemplateReaderMock(),
        );

        // Act
        $response = $taskManifestGenerator->generate($requestDto);

        // Assert
        $this->assertSame('test/Task.yaml', $response->getCreatedFileName());
    }

    /**
     * @param string $filePath
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface
     */
    protected function createManifestWriterMock(string $filePath): ManifestWriterInterface
    {
        $manifestWriterMock = $this->createMock(ManifestWriterInterface::class);
        $manifestWriterMock->expects($this->once())->method('write')->willReturn($filePath);

        return $manifestWriterMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface
     */
    protected function createTemplateReaderMock(): TemplateReaderInterface
    {
        $templateReaderMock = $this->createMock(TemplateReaderInterface::class);
        $templateReaderMock->expects($this->once())->method('readTemplate');

        return $templateReaderMock;
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
