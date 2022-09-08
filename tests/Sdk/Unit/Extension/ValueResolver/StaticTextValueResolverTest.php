<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolver\StaticTextValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class StaticTextValueResolverTest extends Unit
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
    public function testGetValueArray(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(['value1', 'value2']);
        $valueResolver = new StaticTextValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame(['\'value1\'', '\'value2\''], $value);
    }

    /**
     * @return void
     */
    public function testGetValueEmpty(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(false);
        $valueResolver = new StaticTextValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertNull($value);
    }

    /**
     * @return void
     */
    public function testGetValueString(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('value');
        $valueResolver = new StaticTextValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('\'value\'', $value);
    }
}
