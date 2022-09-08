<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolver\NamespaceValueResolver;
use SprykerSdk\Sdk\Extension\ValueResolver\SprykCodeLevelValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class SprykCodeLevelValueResolverTest extends Unit
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
        $this->assertSame('project', $valueResolver->getDefaultValue());
        $this->assertSame('string', $valueResolver->getType());
        $this->assertSame('mode', $valueResolver->getAlias());
        $this->assertSame('CORE', $valueResolver->getId());
    }
}
