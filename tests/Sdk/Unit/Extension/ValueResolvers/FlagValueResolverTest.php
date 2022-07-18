<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolvers\FlagValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class FlagValueResolverTest extends Unit
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
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->context = $this->createMock(ContextInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetValueIfEmpty(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);
        $valueResolver = new FlagValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertEmpty($value);
    }

    /**
     * @return void
     */
    public function testGetValueAliasName(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(true);
        $valueResolver = new FlagValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('--key', $value);
    }

    /**
     * @return void
     */
    public function testGetValueFlagName(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(true);
        $valueResolver = new FlagValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '', 'flag' => 'one']);

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('--one', $value);
    }
}
