<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\RestApi\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\RestApi\DependencyInjection\SprykerSdkRestApiExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SprykerSdkRestApiExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\DependencyInjection\SprykerSdkRestApiExtension
     */
    protected SprykerSdkRestApiExtension $sprykerSdkRestApiExtension;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sprykerSdkRestApiExtension = new SprykerSdkRestApiExtension();
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
        $this->sprykerSdkRestApiExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
