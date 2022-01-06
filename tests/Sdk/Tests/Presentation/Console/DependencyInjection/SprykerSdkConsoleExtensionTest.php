<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Presentation\Console\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\SprykerSdkConsoleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SprykerSdkConsoleExtensionTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\DependencyInjection\SprykerSdkConsoleExtension
     */
    protected SprykerSdkConsoleExtension $sprykerSdkConsoleExtension;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sprykerSdkConsoleExtension = new SprykerSdkConsoleExtension();
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
        $this->sprykerSdkConsoleExtension->load($configs, $containerBuilder);

        // Assert
        $this->assertNotEmpty($containerBuilder->getResources());
    }
}
