<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolvers\ConfigPathValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class ConfigPathValueResolverTest extends Unit
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
    public function testGetValueProjectDir(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('composer.json');
        $valueResolver = new ConfigPathValueResolver($this->valueReceiver);
        // Act
        $value = $valueResolver->getValue($this->context, ['project_dir' => '.', 'sdk_dir' => 'non_exist']);

        // Assert
        $this->assertSame('./composer.json', $value);
    }

    /**
     * @return void
     */
    public function testGetValueSdkDir(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('composer.json');
        $valueResolver = new ConfigPathValueResolver($this->valueReceiver);
        // Act
        $value = $valueResolver->getValue($this->context, ['project_dir' => 'non_exist', 'sdk_dir' => '.']);

        // Assert
        $this->assertSame('./composer.json', $value);
    }
}
