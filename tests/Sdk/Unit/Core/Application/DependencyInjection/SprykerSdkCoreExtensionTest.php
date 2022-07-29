<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\DependencyInjection\SprykerSdkCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group DependencyInjection
 * @group SprykerSdkCoreExtensionTest
 */
class SprykerSdkCoreExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\DependencyInjection\SprykerSdkCoreExtension
     */
    protected SprykerSdkCoreExtension $sprykerSdkCoreExtension;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sprykerSdkCoreExtension = new SprykerSdkCoreExtension();
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
        $this->sprykerSdkCoreExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
