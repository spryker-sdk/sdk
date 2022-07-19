<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolvers\NamespaceValueResolver;
use SprykerSdk\Sdk\Extension\ValueResolvers\SprykCodeLevelValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class SprykCodeLevelValueResolverTest extends Unit
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
    public function testGetValueProject(): void
    {
        // Arrange
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn([]);
        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);

        // Act
        $value = $valueResolver->getValue($this->context, []);

        // Assert
        $this->assertSame('project', $value);
    }

    /**
     * @return void
     */
    public function testGetValueCore(): void
    {
        // Arrange
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn(['%' . NamespaceValueResolver::ALIAS . '%' => 'test']);

        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);

        // Act
        $value = $valueResolver->getValue($this->context, ['coreNamespaces' => ['test']]);

        // Assert
        $this->assertSame('core', $value);
    }

    /**
     * @return void
     */
    public function testGetValueDefault(): void
    {
        // Arrange
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn(['%' . NamespaceValueResolver::ALIAS . '%' => 'test']);

        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);

        // Act
        $value = $valueResolver->getValue($this->context, ['coreNamespaces' => 'none']);

        // Assert
        $this->assertSame('project', $value);
    }
}
