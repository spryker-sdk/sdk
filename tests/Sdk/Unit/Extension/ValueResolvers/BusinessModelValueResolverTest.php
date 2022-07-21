<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolvers\BusinessModelValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class BusinessModelValueResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface
     */
    protected ValueReceiverInterface $valueReceiver;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(ValueReceiverInterface::class);
        $this->context = $this->createMock(ContextInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetValueIfEmpty(): void
    {
        // Arrange
        $repositories = [
            'b2b' => 'https://github.com/spryker-shop/b2b-demo-shop.git',
            'b2c' => 'https://github.com/spryker-shop/b2c-demo-shop.git',
        ];
        $receiverValue = new ReceiverValue(
            'Choose project for installation',
            array_key_first($repositories),
            'string',
            array_keys($repositories),
        );
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->with($receiverValue)
            ->willReturn('b2b');

        $valueResolver = new BusinessModelValueResolver($this->valueReceiver);

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('https://github.com/spryker-shop/b2b-demo-shop.git', $value);
    }
}
