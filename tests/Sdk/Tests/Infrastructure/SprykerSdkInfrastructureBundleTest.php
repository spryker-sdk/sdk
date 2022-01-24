<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Infrastructure;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\DependencyInjection\SprykerSdkInfrastructureExtension;
use SprykerSdk\Sdk\Infrastructure\SprykerSdkInfrastructureBundle;

class SprykerSdkInfrastructureBundleTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\SprykerSdkInfrastructureBundle
     */
    protected SprykerSdkInfrastructureBundle $sprykerSdkInfrastructureBundle;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sprykerSdkInfrastructureBundle = new SprykerSdkInfrastructureBundle();
    }

    /**
     * @return void
     */
    public function testGetContainerInfrastructureShouldReturnSdkInfrastructure(): void
    {
        // Act
        $extension = $this->sprykerSdkInfrastructureBundle->getContainerExtension();

        // Assert
        $this->assertInstanceOf(SprykerSdkInfrastructureExtension::class, $extension);
    }
}
