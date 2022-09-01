<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;

/**
 * @group Unit
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ContextFactoryTest
 */
class ContextFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetContext(): void
    {
        //Arrange
        $contextFactory = new ContextFactory();

        //Act
        $foundContext = $contextFactory->getContext();

        //Assert
        $this->assertEquals(new Context(), $foundContext);
    }

    /**
     * @return void
     */
    public function testGetContextReturnsCachedInstance(): void
    {
        //Arrange
        $contextFactory = new ContextFactory();
        $expectedContext = $contextFactory->getContext();
        $expectedContext->setName('name');

        //Act
        $actualContext = $contextFactory->getContext();

        //Assert
        $this->assertSame($expectedContext, $actualContext);
    }
}
