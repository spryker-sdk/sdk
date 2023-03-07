<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\DefaultContextReceiverInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group ContextFactoryTest
 * Add your own group annotations below this line
 */
class ContextFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetContext(): void
    {
        //Arrange
        $defaultContextMock = $this->createMock(DefaultContextReceiverInterface::class);
        $defaultContextMock->expects($this->once())
            ->method('getFormat')
            ->willReturn('output');
        $contextFactory = new ContextFactory($defaultContextMock);
        $context = new Context();
        $context->setFormat('output');

        //Act
        $foundContext = $contextFactory->getContext();

        //Assert
        $this->assertEquals($context, $foundContext);
    }

    /**
     * @return void
     */
    public function testHasContext(): void
    {
        //Arrange
        $defaultContextMock = $this->createMock(DefaultContextReceiverInterface::class);
        $defaultContextMock->expects($this->once())
            ->method('getFormat')
            ->willReturn('output');
        $contextFactory = new ContextFactory($defaultContextMock);
        $context = new Context();
        $context->setFormat('output');
        $contextFactory->getContext();

        //Act
        $hasContext = $contextFactory->hasContext();

        //Assert
        $this->assertTrue($hasContext);
    }

    /**
     * @return void
     */
    public function testDoesNotHasContext(): void
    {
        //Arrange
        $defaultContextMock = $this->createMock(DefaultContextReceiverInterface::class);
        $contextFactory = new ContextFactory($defaultContextMock);
        //Act
        $hasContext = $contextFactory->hasContext();

        //Assert
        $this->assertFalse($hasContext);
    }

    /**
     * @return void
     */
    public function testGetContextReturnsCachedInstance(): void
    {
        //Arrange
        $defaultContextMock = $this->createMock(DefaultContextReceiverInterface::class);
        $defaultContextMock->expects($this->once())
            ->method('getFormat')
            ->willReturn('output');
        $contextFactory = new ContextFactory($defaultContextMock);
        $expectedContext = $contextFactory->getContext();
        $expectedContext->setName('name');
        $expectedContext->setFormat('output');

        //Act
        $actualContext = $contextFactory->getContext();

        //Assert
        $this->assertSame($expectedContext, $actualContext);
    }
}
