<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\VcsConnector;

use Codeception\Test\Unit;
use VcsConnector\Exception\AdapterDoesNotExistException;
use VcsConnector\Vcs\Adapter\VcsInterface;
use VcsConnector\Vcs\VcsConfigurationResolver;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group VcsConnector
 * @group VcsConfigurationResolverTest
 * Add your own group annotations below this line
 */
class VcsConfigurationResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolve(): void
    {
        // Arrange
        $vcsMock = $this->createMock(VcsInterface::class);
        $vcsConfigurationResolver = new VcsConfigurationResolver(['github' => $vcsMock]);

        // Act
        $vcs = $vcsConfigurationResolver->resolve('github');

        // Assert
        $this->assertInstanceOf(VcsInterface::class, $vcs);
    }

    /**
     * @return void
     */
    public function testCanNotResolve(): void
    {
        // Arrange
        $vcsMock = $this->createMock(VcsInterface::class);
        $vcsConfigurationResolver = new VcsConfigurationResolver(['gitlab' => $vcsMock]);

        // Assert
        $this->expectException(AdapterDoesNotExistException::class);

        // Act
        $vcsConfigurationResolver->resolve('github');
    }
}
