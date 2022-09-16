<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\SprykCodeLevelValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class SprykCodeLevelValueResolverTest extends Unit
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
    public function testGetValueProject(): void
    {
        // Arrange
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn([]);
        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);
        $valueResolver->configure(['defaultValue' => 'project']);

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
        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn(['%' . $valueResolver->getAlias() . '%' => 'test']);
        $valueResolver->configure(['defaultValue' => 'project']);

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
        $valueResolver = new SprykCodeLevelValueResolver($this->valueReceiver);
        $this->context
            ->expects($this->once())
            ->method('getResolvedValues')
            ->willReturn(['%' . $valueResolver->getAlias() . '%' => 'test']);

        $valueResolver->configure(['defaultValue' => 'project']);

        // Act
        $value = $valueResolver->getValue($this->context, ['coreNamespaces' => 'none']);

        // Assert
        $this->assertSame('project', $value);
        $this->assertSame('project', $valueResolver->getDefaultValue());
        $this->assertSame('string', $valueResolver->getType());
        $this->assertNull($valueResolver->getAlias());
        $this->assertSame('CORE', $valueResolver->getId());
    }
}
