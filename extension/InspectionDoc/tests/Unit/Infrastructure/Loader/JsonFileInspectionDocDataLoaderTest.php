<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Tests\Unit\Infrastructure\Loader;

use Codeception\Test\Unit;
use InspectionDoc\Infrastructure\Loader\JsonFileInspectionDocDataLoader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Infrastructure
 * @group Loader
 * @group JsonFileInspectionDocDataLoaderTest
 * Add your own group annotations below this line
 */
class JsonFileInspectionDocDataLoaderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetInspectionDocs(): void
    {
        // Arrange
        $vfsFile = new vfsStreamFile('test.yaml');
        $vfsFile->setContent('[
            {
            "inspectionId": "SprykerStrict.TypeHints.ParameterTypeHint",
            "link": "/docs/sdk/dev/development-tools/sniffs/spryker-strict/type-hints/parameter-type-hint-sniff.html"
            }
        ]');

        $vfsStream = vfsStream::setup();
        $vfsStream->addChild($vfsFile);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $jsonFileInspectionDocDataLoader = new JsonFileInspectionDocDataLoader($vfsFile->url(), $loggerMock);

        // Act
        $data = $jsonFileInspectionDocDataLoader->getInspectionDocs();

        // Assert
        $this->assertSame([
            'SprykerStrict.TypeHints.ParameterTypeHint' => [
                'inspectionId' => 'SprykerStrict.TypeHints.ParameterTypeHint',
                'link' => '/docs/sdk/dev/development-tools/sniffs/spryker-strict/type-hints/parameter-type-hint-sniff.html',
            ],
        ], $data);
    }

    /**
     * @return void
     */
    public function testGetInspectionDocsFileDoesNotExist(): void
    {
        // Arrange
        $vfsFile = new vfsStreamFile('test.yaml');

        $vfsStream = vfsStream::setup();
        $vfsStream->addChild($vfsFile);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock
            ->expects($this->once())
            ->method('error');
        $jsonFileInspectionDocDataLoader = new JsonFileInspectionDocDataLoader($vfsFile->url(), $loggerMock);

        // Act
        $data = $jsonFileInspectionDocDataLoader->getInspectionDocs();

        // Assert
        $this->assertSame([], $data);
    }

    /**
     * @return void
     */
    public function testGetInspectionDocsWrongStructure(): void
    {
        // Arrange
        $vfsFile = new vfsStreamFile('test.yaml');
        $vfsFile->setContent('');

        $vfsStream = vfsStream::setup();
        $vfsStream->addChild($vfsFile);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock
            ->expects($this->once())
            ->method('error');
        $jsonFileInspectionDocDataLoader = new JsonFileInspectionDocDataLoader($vfsFile->url(), $loggerMock);

        // Act
        $data = $jsonFileInspectionDocDataLoader->getInspectionDocs();

        // Assert
        $this->assertSame([], $data);
    }
}
