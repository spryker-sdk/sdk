<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolver\AppTypeValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class AppTypeValueResolverTest extends Unit
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
            'boilerplate' => 'https://github.com/spryker/project-boilerplate',
        ];
        $receiverValue = new ReceiverValue(
            'App template to use for creation',
            array_key_first($repositories),
            'string',
            array_keys($repositories),
        );
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->with($receiverValue)
            ->willReturn('boilerplate');

        $valueResolver = new AppTypeValueResolver($this->valueReceiver);

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('https://github.com/spryker/project-boilerplate', $value);
    }
}
