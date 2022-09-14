<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

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
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface
     */
    protected ReceiverInterface $valueReceiver;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(ReceiverInterface::class);
        $this->context = $this->createMock(ContextInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetValueWithProvidedOption(): void
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
    public function testGetValueWithMissingSettingException(): void
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
     * @return \SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver
     */
    protected function createValueResolver(): AbstractValueResolver
    {
        return new class ($this->valueReceiver) extends AbstractValueResolver {
            /**
             * {@inheritDoc}
             *
             * @return string
             */
            public function getId(): string
            {
                return 'test';
            }

            /**
             * {@inheritDoc}
             *
             * @return array<string>
             */
            public function getSettingPaths(): array
            {
                return [];
            }

            /**
             * {@inheritDoc}
             *
             * @return string|null
             */
            public function getAlias(): ?string
            {
                return 'test';
            }

            /**
             * {@inheritDoc}
             *
             * @return string
             */
            public function getDescription(): string
            {
                return 'Test desctiption';
            }

            /**
             * {@inheritDoc}
             *
             * @return string
             */
            public function getType(): string
            {
                return 'string';
            }

            /**
             * {@inheritDoc}
             *
             * @return mixed
             */
            public function getDefaultValue()
            {
                return null;
            }

            /**
             * {@inheritDoc}
             *
             * @return array<string>
             */
            protected function getRequiredSettingPaths(): array
            {
                return ['testKey'];
            }

            /**
             * {@inheritDoc}
             *
             * @param array<string, mixed> $settingValues
             *
             * @return mixed
             */
            protected function getValueFromSettings(array $settingValues)
            {
                return [];
            }
        };
    }
}
