<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\Exception\UnresolvableValueExceptionException;
use SprykerSdk\Sdk\Extension\ValueResolver\PriorityPathValueResolver;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidConfigurationException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group ValueResolver
 * @group PriorityPathValueResolverTest
 * Add your own group annotations below this line
 */
class PriorityPathValueResolverTest extends Unit
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
    public function testGetValueWithoutSettings(): void
    {
        // Arrange
        $valueResolver = new PriorityPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '']);

        // Assert
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $valueResolver->getValue($this->context, ['defaultValue' => './']);
    }

    /**
     * @return void
     */
    public function testGetValueFromSettings(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('hasRequestItem')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('getRequestItem')
            ->willReturn('tests');
        $valueResolver = new PriorityPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '', 'settingPaths' => ['one', 'two']]);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value', 'one' => 'nonExist', 'two' => '.']);

        // Assert
        $this->assertSame('./tests', $value);
    }

    /**
     * @return void
     */
    public function testGetValueException(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('hasRequestItem')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('getRequestItem')
            ->willReturn('./none');
        $valueResolver = new PriorityPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '', 'settingPaths' => ['test' => 'test']]);

        // Assert
        $this->expectException(UnresolvableValueExceptionException::class);
        $this->expectExceptionMessage('Invalid path provided.');

        // Act
        $valueResolver->getValue($this->context, ['defaultValue' => './data/file.txt']);
    }

    /**
     * @return void
     */
    public function testGetValueExceptionAbsolutePath(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('hasRequestItem')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('getRequestItem')
            ->willReturn('/none');
        $valueResolver = new PriorityPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '', 'settingPaths' => ['test' => 'test']]);

        // Assert
        $this->expectException(UnresolvableValueExceptionException::class);
        $this->expectExceptionMessage('Absolute path is forbidden due to security reasons.');

        // Act
        $valueResolver->getValue($this->context, []);
    }

    /**
     * @return void
     */
    public function testGetValueExceptionParentLevelPath(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('hasRequestItem')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('getRequestItem')
            ->willReturn('../none');
        $valueResolver = new PriorityPathValueResolver($this->valueReceiver);
        $valueResolver->configure(['name' => 'key', 'description' => '', 'settingPaths' => ['test' => 'test']]);

        // Assert
        $this->expectException(UnresolvableValueExceptionException::class);
        $this->expectExceptionMessage('Path ../ is forbidden due to security reasons.');

        // Act
        $valueResolver->getValue($this->context, []);
    }
}
