<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\ValueResolvers\PbcPhpVersionValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class PbcPhpVersionValueResolverTest extends Unit
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
    public function testGetValue(): void
    {
        // Arrange
        $receiverValue = new ReceiverValue(
            'PHP version to use for the PBC',
            array_key_first(PbcPhpVersionValueResolver::PHP_VERSIONS),
            'string',
            array_keys(PbcPhpVersionValueResolver::PHP_VERSIONS),
        );
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->with($receiverValue);
        $valueResolver = new PbcPhpVersionValueResolver($this->valueReceiver);

        // Act
        $valueResolver->getValue($this->context, []);
    }
}
