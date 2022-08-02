<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use LogicException;
use SprykerSdk\Sdk\Core\Application\Service\ContextStorage;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ContextStorageTest
 */
class ContextStorageTest extends Unit
{
    /**
     * @return void
     */
    public function testThrowExceptionWhenContextIsNotSet(): void
    {
        //Arrange
        $storage = new ContextStorage();

        //Act
        $this->expectException(LogicException::class);
        $storage->getContext();
    }

    /**
     * @return void
     */
    public function testReturnsContextWhenContextIsSet(): void
    {
        //Arrange
        $storage = new ContextStorage();
        $context = $this->createMock(ContextInterface::class);

        //Act
        $storage->setContext($context);
        $foundContext = $storage->getContext();

        //Assert
        $this->assertSame($context, $foundContext);
    }
}
