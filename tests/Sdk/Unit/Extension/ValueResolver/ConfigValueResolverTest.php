<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\ConfigPathValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group ValueResolver
 * @group ConfigValueResolverTest
 * Add your own group annotations below this line
 */
class ConfigValueResolverTest extends Unit
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
    public function testGetValue(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('');
        $valueResolver = new ConfigPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value']);

        // Assert
        $this->assertSame('/', $value);
    }
}
