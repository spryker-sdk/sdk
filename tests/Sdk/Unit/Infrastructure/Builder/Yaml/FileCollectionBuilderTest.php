<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilderInterface;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group FileCollectionBuilderTest
 */
class FileCollectionBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilderInterface
     */
    protected FileCollectionBuilderInterface $fileBuilder;

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
        $this->fileBuilder = new FileCollectionBuilder();
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
        $this->assertSame($files->getTaskData()['files'][0]['path'], $result[0]->getPath());
        $this->assertSame($files->getTaskData()['files'][0]['content'], $result[0]->getContent());
        $this->assertSame($files->getTaskData()['files'][1]['path'], $result[1]->getPath());
        $this->assertSame($files->getTaskData()['files'][1]['content'], $result[1]->getContent());
    }
}
