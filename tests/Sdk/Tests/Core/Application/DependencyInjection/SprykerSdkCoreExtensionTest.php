<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\DependencyInjection\SprykerSdkCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SprykerSdkCoreExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\DependencyInjection\SprykerSdkCoreExtension
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
        $result = $this->sprykerSdkCoreExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNull($result);
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
