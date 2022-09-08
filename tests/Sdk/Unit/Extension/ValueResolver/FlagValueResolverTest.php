<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolver\FlagValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class FlagValueResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface
     */
    protected ReceiverInterface $valueReceiver;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(ReceiverInterface::class);
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
