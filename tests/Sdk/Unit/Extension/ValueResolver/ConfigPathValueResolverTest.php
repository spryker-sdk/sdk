<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Domain\Enum\Setting;
use SprykerSdk\Sdk\Extension\ValueResolver\ConfigPathValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @group Sdk
 * @group Extension
 * @group ValueResolver
 * @group ConfigPathValueResolverTest
 */
class ConfigPathValueResolverTest extends Unit
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
        $valueResolver->configure(['alias' => 'test', 'defaultValue' => 'composer.json']);
        // Act
        $value = $valueResolver->getValue($this->context, [Setting::PATH_PROJECT_DIR => '.', Setting::PATH_SDK_DIR => 'non_exist']);

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
        $valueResolver->configure(['alias' => 'test', 'defaultValue' => 'composer.json']);
        // Act
        $value = $valueResolver->getValue($this->context, [Setting::PATH_PROJECT_DIR => 'non_exist', Setting::PATH_SDK_DIR => '.']);

        // Assert
        $this->assertSame('./composer.json', $value);
    }
}
