<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Console;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\SprykerSdkConsoleExtension;
use SprykerSdk\Sdk\Presentation\Console\SprykerSdkConsoleBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Presentation
 * @group Console
 * @group SprykerSdkConsoleBundleTest
 * Add your own group annotations below this line
 */
class SprykerSdkConsoleBundleTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\SprykerSdkConsoleBundle
     */
    protected SprykerSdkConsoleBundle $sprykerSdkConsoleBundle;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sprykerSdkConsoleBundle = new SprykerSdkConsoleBundle();
    }

    /**
     * @return void
     */
    public function testGetContainerPresentationShouldReturnSdkPresentation(): void
    {
        // Act
        $extension = $this->sprykerSdkConsoleBundle->getContainerExtension();

        // Assert
        $this->assertInstanceOf(SprykerSdkConsoleExtension::class, $extension);
    }

    /**
     * @return void
     */
    public function testBuildShouldAddCompilerPassToContainer(): void
    {
        // Arrange
        $containerBuilder = new ContainerBuilder();

        // Act
        $this->sprykerSdkConsoleBundle->build($containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
