<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\Filesystem;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group FilesystemTest
 */
class FilesystemTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Filesystem
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
