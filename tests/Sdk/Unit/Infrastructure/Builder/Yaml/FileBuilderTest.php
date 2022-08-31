<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilderInterface;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\FileInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group FileBuilderTest
 */
class FileBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilderInterface
     */
    protected FileBuilderInterface $fileBuilder;

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
        $this->fileBuilder = new FileBuilder();
    }

    /**
     * @return void
     */
    public function testBuildFilesShouldReturnArrayOfFiles(): void
    {
        // Arrange
        $files = $this->tester->createFilesData();

        // Act
        $result = $this->fileBuilder->buildFiles($files);

        // Assert
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(FileInterface::class, $result);
        $this->assertSame($files['files'][0]['path'], $result[0]->getPath());
        $this->assertSame($files['files'][0]['content'], $result[0]->getContent());
        $this->assertSame($files['files'][1]['path'], $result[1]->getPath());
        $this->assertSame($files['files'][1]['content'], $result[1]->getContent());
    }
}
