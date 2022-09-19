<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @group Sdk
 * @group Extension
 * @group ValueResolver
 * @group StaticValueResolverTest
 */
class StaticValueResolverTest extends Unit
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
    public function testGetValueOption(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('value');
        $valueResolver = new StaticValueResolver($this->valueReceiver);
        $valueResolver->configure(['option' => 'test', 'name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value']);

        // Assert
        $this->assertSame('--test=\'value\'', $value);
    }

    /**
     * @return void
     */
    public function testGetValueOptions(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn(['value1', 'value2']);
        $valueResolver = new StaticValueResolver($this->valueReceiver);
        $valueResolver->configure(['option' => 'test', 'name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value']);

        // Assert
        $this->assertSame(['--test=\'value1\'', '--test=\'value2\''], $value);
    }

    /**
     * @return void
     */
    public function testGetValueArgument(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('value');
        $valueResolver = new StaticValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value']);

        // Assert
        $this->assertSame('\'value\'', $value);
    }
}
