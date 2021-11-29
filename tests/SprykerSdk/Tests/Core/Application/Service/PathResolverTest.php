<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\PathResolver;

class PathResolverTest extends Unit
{
    /**
     * @var string
     */
    public const SDK_DIRECTORY = '/some/dir';

    /**
     * @return void
     */
    public function testAbsolutePathIsResolved(): void
    {
        $expectedAbsolutePath = '/absolute/path';
        $pathResolver = new PathResolver(static::SDK_DIRECTORY);
        $this->assertSame(
            $expectedAbsolutePath,
            $pathResolver->getResolveRelativePath($expectedAbsolutePath),
        );
    }

    /**
     * @return void
     */
    public function testRelativePathIsResolved(): void
    {
        $expectedAbsolutePath = 'absolute/path';
        $pathResolver = new PathResolver(static::SDK_DIRECTORY);
        $this->assertSame(
            static::SDK_DIRECTORY . '/' . $expectedAbsolutePath,
            $pathResolver->getResolveRelativePath($expectedAbsolutePath),
        );
    }
}
