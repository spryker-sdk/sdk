<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolver\AppPhpVersionValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group ValueResolver
 * @group AppPhpVersionValueResolverTest
 * Add your own group annotations below this line
 */
class AppPhpVersionValueResolverTest extends Unit
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
        $receiverValue = new ReceiverValue(
            'PHP version to use for the App',
            array_key_first(AppPhpVersionValueResolver::PHP_VERSIONS),
            'string',
            array_keys(AppPhpVersionValueResolver::PHP_VERSIONS),
            'app_php_version',
        );
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->with($receiverValue);
        $valueResolver = new AppPhpVersionValueResolver($this->valueReceiver);

        // Act
        $valueResolver->getValue($this->context, []);
    }
}
