<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolvers\PCSystemValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class PCSystemValueResolverTest extends Unit
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
    public function testGetValueLinux(): void
    {
        // Arrange
        $valueResolver = new PCSystemValueResolver($this->valueReceiver, 'system Linux system');
        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame(PCSystemValueResolver::LINUX, $value);
    }

    /**
     * @return void
     */
    public function testGetValueMac(): void
    {
        // Arrange
        $valueResolver = new PCSystemValueResolver($this->valueReceiver, 'system Darwin system');

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame(PCSystemValueResolver::MAC, $value);
    }

    /**
     * @return void
     */
    public function testGetValueMackARM(): void
    {
        // Arrange
        $valueResolver = new PCSystemValueResolver($this->valueReceiver, 'system Darwin system ARM64 system');

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame(PCSystemValueResolver::MAC_ARM, $value);
    }

    /**
     * @return void
     */
    public function testGetValueUndefined(): void
    {
        // Arrange
        $valueResolver = new PCSystemValueResolver($this->valueReceiver, 'test system');

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('', $value);
    }
}
