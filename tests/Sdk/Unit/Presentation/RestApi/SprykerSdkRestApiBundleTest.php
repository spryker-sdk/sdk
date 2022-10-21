<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\RestApi;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\RestApi\DependencyInjection\SprykerSdkRestApiExtension;
use SprykerSdk\Sdk\Presentation\RestApi\SprykerSdkRestApiBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SprykerSdkRestApiBundleTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\SprykerSdkRestApiBundle
     */
    protected SprykerSdkRestApiBundle $sprykerSdkRestApiBundle;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sprykerSdkRestApiBundle = new SprykerSdkRestApiBundle();
    }

    /**
     * @return void
     */
    public function testGetContainerPresentationShouldReturnSdkPresentation(): void
    {
        // Act
        $extension = $this->sprykerSdkRestApiBundle->getContainerExtension();

        // Assert
        $this->assertInstanceOf(SprykerSdkRestApiExtension::class, $extension);
    }

    /**
     * @return void
     */
    public function testBuildShouldAddCompilerPassToContainer(): void
    {
        // Arrange
        $containerBuilder = new ContainerBuilder();

        // Act
        $this->sprykerSdkRestApiBundle->build($containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
