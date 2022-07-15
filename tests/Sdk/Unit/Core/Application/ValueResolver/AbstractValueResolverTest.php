<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group CommandExecutorTest
 */
class AbstractValueResolverTest extends Unit
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
    public function testGetValueWithOption(): void
    {
        // Arrange
        $valueResolver = $this->createValueResolver();
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->with('test')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->with('test')
            ->willReturn(true);
        // Act
        $value = $valueResolver->getValue($this->context, ['testKey' => 'testValue']);

        // Assert
        $this->assertTrue($value);
    }

    /**
     * @return void
     */
    public function testGetValueException(): void
    {
        // Arrange
        $valueResolver = $this->createValueResolver();
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        // Assert
        $this->expectException(MissingSettingException::class);

        // Act
        $valueResolver->getValue($this->context, []);
    }

    /**
     * @return void
     */
    public function testGetValue(): void
    {
        // Arrange
        $valueResolver = $this->createValueResolver();
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->with('test')
            ->willReturn(false);
        $this->valueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->willReturn(null);

        // Act
        $value = $valueResolver->getValue($this->context, ['testKey' => true], false);

        // Assert
        $this->assertNull($value);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver
     */
    protected function createValueResolver(): AbstractValueResolver
    {
        return new class ($this->valueReceiver) extends AbstractValueResolver {
            /**
             * @return string
             */
            public function getId(): string
            {
                return 'test';
            }

            /**
             * @return array<string>
             */
            public function getSettingPaths(): array
            {
                return [];
            }

            /**
             * @return string|null
             */
            public function getAlias(): ?string
            {
                return 'test';
            }

            /**
             * @return string
             */
            public function getDescription(): string
            {
                return 'Test desctiption';
            }

            /**
             * @return string
             */
            public function getType(): string
            {
                return 'string';
            }

            /**
             * @return mixed
             */
            public function getDefaultValue(): mixed
            {
                return null;
            }

            /**
             * @return array<string>
             */
            protected function getRequiredSettingPaths(): array
            {
                return ['testKey'];
            }

            /**
             * @param array<string, mixed> $settingValues
             *
             * @return mixed
             */
            protected function getValueFromSettings(array $settingValues): mixed
            {
                return [];
            }
        };
    }
}
