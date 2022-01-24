<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\DependencyInjection\SprykerSdkCoreExtension;
use SprykerSdk\Sdk\Core\SprykerSdkCoreBundle;

class SprykerSdkCoreBundleTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\SprykerSdkCoreBundle
     */
    protected SprykerSdkCoreBundle $sprykerSdkCoreBundle;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sprykerSdkCoreBundle = new SprykerSdkCoreBundle();
    }

    /**
     * @return void
     */
    public function testGetContainerExtensionShouldReturnSdkExtension(): void
    {
        // Act
        $extension = $this->sprykerSdkCoreBundle->getContainerExtension();

        // Assert
        $this->assertInstanceOf(SprykerSdkCoreExtension::class, $extension);
    }
}
