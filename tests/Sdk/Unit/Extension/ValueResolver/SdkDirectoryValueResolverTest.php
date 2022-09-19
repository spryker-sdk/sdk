<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\SdkDirectoryValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @group Sdk
 * @group Extension
 * @group ValueResolver
 * @group SdkDirectoryValueResolverTest
 */
class SdkDirectoryValueResolverTest extends Unit
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
    public function testGetValueBaseDir(): void
    {
        // Arrange
        $valueResolver = new SdkDirectoryValueResolver($this->valueReceiver, '../');

        // Act
        $value = $valueResolver->getValue($this->context, [], true);

        // Assert
        $this->assertSame('../', $value);
    }

    /**
     * @return void
     */
    public function testGetValueRealPath(): void
    {
        // Arrange
        $valueResolver = new SdkDirectoryValueResolver($this->valueReceiver, '../');

        // Act
        $value = $valueResolver->getValue($this->context, [SdkDirectoryValueResolver::SETTING_SDK_DIR => '../'], true);

        // Assert
        $this->assertSame(realpath('../'), $value);
    }

    /**
     * @return void
     */
    public function testGetValueFlagName(): void
    {
        // Arrange
        $valueResolver = new SdkDirectoryValueResolver($this->valueReceiver, '../');

        // Act
        $value = $valueResolver->getValue($this->context, [SdkDirectoryValueResolver::SETTING_SDK_DIR => '../'], true);

        // Assert
        $this->assertSame(realpath('../'), $value);
    }
}
