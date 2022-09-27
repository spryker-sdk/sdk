<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\DependencyInjection\SprykerSdkExtensionExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @group Sdk
 * @group Extension
 * @group DependencyInjection
 * @group SprykerSdkExtensionExtensionTest
 */
class SprykerSdkExtensionExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Extension\DependencyInjection\SprykerSdkExtensionExtension
     */
    protected SprykerSdkExtensionExtension $sprykerSdkExtensionExtension;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sprykerSdkExtensionExtension = new SprykerSdkExtensionExtension();
    }

    /**
     * @return void
     */
    public function testCreateExtension(): void
    {
        // Arrange
        $containerBuilder = new ContainerBuilder();
        $configs = [];

        // Act
        $this->sprykerSdkExtensionExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
