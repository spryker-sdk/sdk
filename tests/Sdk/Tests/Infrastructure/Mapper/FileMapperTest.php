<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Mapper\FileMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

class FileMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\FileMapper
     */
    protected FileMapper $fileMapper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fileMapper = new FileMapper();
    }

    /**
     * @return void
     */
    public function testMapFileShouldReturnInfrastructureFile(): void
    {
        // Arrange
        $file = $this->tester->createFile('/foo/path', 'content');

        // Act
        $result = $this->fileMapper->mapFile($file);

        // Assert
        $this->assertSame($file->getPath(), $result->getPath());
        $this->assertSame($file->getContent(), $result->getContent());
    }
}
