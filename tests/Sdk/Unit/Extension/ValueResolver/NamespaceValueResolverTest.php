<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolver\NamespaceValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\Setting;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group ValueResolver
 * @group NamespaceValueResolverTest
 * Add your own group annotations below this line
 */
class NamespaceValueResolverTest extends Unit
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
     * @group textttt
     *
     * @return void
     */
    public function testGetValueIfEmpty(): void
    {
        // Arrange
        $receiverValue = new ReceiverValue(
            'namespace',
            'Namespace name',
            'Pyz',
            'string',
            ['Pyz', 'test2', 'test3'],
        );
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->with($receiverValue)
            ->willReturn('Pyz');

        $valueResolver = new NamespaceValueResolver($this->valueReceiver);
        $valueResolver->configure(['defaultValue' => 'Pyz', 'description' => 'Namespace name']);

        // Act
        $valueResolver->getValue($this->context, [Setting::PATH_PROJECT_NAMESPACES => ['Pyz', 'test2'], Setting::PATH_CORE_NAMESPACES => ['test3']]);
    }
}
