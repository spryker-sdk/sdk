<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Extension;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\DependencyInjection\SprykerSdkExtensionExtension;
use SprykerSdk\Sdk\Extension\SprykerSdkExtensionBundle;

class SprykerSdkExtensionBundleTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Extension\SprykerSdkExtensionBundle
     */
    protected SprykerSdkExtensionBundle $sprykerSdkExtensionBundle;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sprykerSdkExtensionBundle = new SprykerSdkExtensionBundle();
    }

    /**
     * @return void
     */
    public function testGetContainerExtensionShouldReturnSdkExtension(): void
    {
        // Act
        $extension = $this->sprykerSdkExtensionBundle->getContainerExtension();

        // Assert
        $this->assertInstanceOf(SprykerSdkExtensionExtension::class, $extension);
    }
}
