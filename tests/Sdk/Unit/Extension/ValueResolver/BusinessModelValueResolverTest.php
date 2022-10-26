<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolver\BusinessModelValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group ValueResolver
 * @group BusinessModelValueResolverTest
 * Add your own group annotations below this line
 */
class BusinessModelValueResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $valueReceiver;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(InteractionProcessorInterface::class);
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
            'business_model_url',
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
