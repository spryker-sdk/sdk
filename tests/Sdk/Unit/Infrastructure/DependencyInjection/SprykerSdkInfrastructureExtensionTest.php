<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\DependencyInjection\SprykerSdkInfrastructureExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group DependencyInjection
 * @group SprykerSdkInfrastructureExtensionTest
 * Add your own group annotations below this line
 */
class SprykerSdkInfrastructureExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\DependencyInjection\SprykerSdkInfrastructureExtension
     */
    protected SprykerSdkInfrastructureExtension $sprykerSdkInfrastructureExtension;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sprykerSdkInfrastructureExtension = new SprykerSdkInfrastructureExtension();
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
        $this->sprykerSdkInfrastructureExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
