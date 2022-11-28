<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;
use SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group PathResolverTest
 * Add your own group annotations below this line
 */
class PathResolverTest extends Unit
{
    /**
     * @var string
     */
    public const SDK_DIRECTORY = '/sdk/dir';

    /**
     * @var string
     */
    public const PROJECT_DIRECTORY = '/project/dir';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->filesystem = $this->createPartialMock(Filesystem::class, ['getcwd']);

        $this->pathResolver = new PathResolver(static::SDK_DIRECTORY, $this->filesystem);
    }

    /**
     * @return void
     */
    public function testGetResolveRelativePathShouldReturnInitialPath(): void
    {
        // Arrange
        $expectedAbsolutePath = '/absolute/path';

        // Act
        $result = $this->pathResolver->getResolveRelativePath($expectedAbsolutePath);

        // Assert
        $this->assertSame($expectedAbsolutePath, $result);
    }

    /**
     * @return void
     */
    public function testGetResolveRelativePathShouldReturnResolvedPath(): void
    {
        // Arrange
        $expectedAbsolutePath = 'absolute/path';

        // Act
        $result = $this->pathResolver->getResolveRelativePath($expectedAbsolutePath);

        // Assert
        $this->assertSame(static::SDK_DIRECTORY . '/' . $expectedAbsolutePath, $result);
    }

    /**
     * @return void
     */
    public function testGetResolveProjectRelativePathShouldReturnInitialPath(): void
    {
        // Arrange
        $expectedAbsolutePath = '/project/absolute/path';
        $this->filesystem
            ->expects($this->never())
            ->method('getcwd');

        // Act
        $result = $this->pathResolver->getResolveProjectRelativePath($expectedAbsolutePath);

        // Assert
        $this->assertSame($expectedAbsolutePath, $result);
    }

    /**
     * @return void
     */
    public function testGetResolveProjectRelativePathShouldReturnResolvedPath(): void
    {
        // Arrange
        $expectedAbsolutePath = 'absolute/path';
        $this->filesystem
            ->expects($this->once())
            ->method('getcwd')
            ->willReturn(static::PROJECT_DIRECTORY);

        // Act
        $result = $this->pathResolver->getResolveProjectRelativePath($expectedAbsolutePath);

        // Assert
        $this->assertSame(static::PROJECT_DIRECTORY . '/' . $expectedAbsolutePath, $result);
    }
}
