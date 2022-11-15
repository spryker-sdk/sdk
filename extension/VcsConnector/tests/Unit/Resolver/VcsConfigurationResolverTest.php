<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Tests\Unit\Resolver;

use Codeception\Test\Unit;
use VcsConnector\Adapter\VcsInterface;
use VcsConnector\Exception\AdapterDoesNotExistException;
use VcsConnector\Resolver\VcsConfigurationResolver;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Resolver
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
