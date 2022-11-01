<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group FilesystemTest
 * Add your own group annotations below this line
 */
class FilesystemTest extends Unit
{
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
        $this->filesystem = new Filesystem();
    }

    /**
     * @return void
     */
    public function testGetcwdShouldReturnString(): void
    {
        // Act
        $result = $this->filesystem->getcwd();

        // Assert
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
}
