<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;
use SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group PlaceholderResolverTest
 */
class PlaceholderResolverTest extends Unit
{
    /**
     * @var string
     */
    protected const VALUE_RESOLVER_ID = 'value_resolver_id';

    /**
     * @var string
     */
    protected const PLACEHOLDER_NAME = 'test_setting';

    /**
     * @var string
     */
    protected const VALUE = 'value';

    /**
     * @var string
     */
    protected const SETTINGS = 'settings';

    /**
     * @var string
     */
    protected const OPTIONAL = 'optional';

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @dataProvider providePlaceholders
     *
     * @param mixed $expectedValue
     * @param array $expectedSettings
     * @param bool $optionalPlaceholder
     *
     * @return void
     */
    public function testResolvePlaceholderWithoutSettings(
        mixed $expectedValue,
        array $expectedSettings,
        bool $optionalPlaceholder
    ): void {
        // Arrange
        $placeholder = $this->tester->createPlaceholder(
            static::PLACEHOLDER_NAME,
            static::VALUE_RESOLVER_ID,
            $optionalPlaceholder,
        );

        $settingRepositoryMock = $this->createSettingRepositoryMock($expectedSettings);
        $valueResolverMock = $this->createValueResolverMock($expectedSettings, $expectedValue);
        $registryMock = $this->createRegistryMock($valueResolverMock);
        $context = new Context();
        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);

        // Act
        $result = $placeholderResolver->resolve($placeholder, $context);

        // Assert
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @return void
     */
    public function testSettingsAreInjectedDuringResolve(): void
    {
        // Arrange
        $expectedSettingKey = static::PLACEHOLDER_NAME;
        $expectedValue = 'some_setting_value';
        $expectedSettings = [
             $expectedSettingKey => $expectedValue,
        ];

        $placeholderMock = $this->tester->createPlaceholder(
            static::PLACEHOLDER_NAME,
            static::VALUE_RESOLVER_ID,
            true,
        );
        $settingRepositoryMock = $this->createSettingRepositoryMock($expectedSettings);
        $valueResolverMock = $this->createMock(ValueResolverInterface::class);
        $valueResolverMock->expects($this->once())
            ->method('getSettingPaths')
            ->willReturn(array_keys($expectedSettings));
        $valueResolverMock->expects($this->once())
            ->method('getValue')
            ->willReturnCallback(function (ContextInterface $context) use ($expectedSettingKey): mixed {
                $this->assertArrayHasKey($expectedSettingKey, $context->getResolvedValues());

                return $context->getResolvedValues()[$expectedSettingKey];
            });
        $registryMock = $this->createRegistryMock($valueResolverMock);

        $context = new Context();
        $context->setResolvedValues($expectedSettings);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);

        // Act
        $result = $placeholderResolver->resolve($placeholderMock, $context);

        // Assert
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @return void
     */
    public function testUnresolvableValueResolver(): void
    {
        // Arrange
        $settingRepositoryMock = $this->createSettingRepositoryMock([]);
        $registryMock = $this->createMock(ValueResolverRegistryInterface::class);
        $registryMock->expects($this->once())
            ->method('has')
            ->willReturn(false);
        $registryMock->expects($this->never())
            ->method('get')
            ->willReturn(null);

        $placeholder = $this->tester->createPlaceholder(
            static::PLACEHOLDER_NAME,
            static::VALUE_RESOLVER_ID,
            false,
        );

        $this->expectException(UnresolvablePlaceholderException::class);
        $this->expectExceptionMessage('Placeholder not resolvable ' . static::VALUE_RESOLVER_ID);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);

        // Act
        $placeholderResolver->getValueResolver($placeholder);
    }

    /**
     * @return void
     */
    public function testConfigurableValueResolver(): void
    {
        // Arrange
        $expectedConfiguration = [
            'key' => 'value',
        ];
        $settingRepositoryMock = $this->createSettingRepositoryMock([]);
        $valueResolverMock = $this->createMock(ConfigurableValueResolverInterface::class);
        $valueResolverMock
            ->method('configure')
            ->willReturnCallback(function ($configuration) use ($expectedConfiguration): void {
                $this->assertSame($expectedConfiguration, $configuration);
            });
        $registryMock = $this->createMock(ValueResolverRegistryInterface::class);
        $registryMock->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $registryMock->expects($this->once())
            ->method('get')
            ->willReturn($valueResolverMock);
        $placeholder = $this->tester->createPlaceholder(
            static::PLACEHOLDER_NAME,
            static::VALUE_RESOLVER_ID,
            false,
            $expectedConfiguration,
        );

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);

        // Act
        $resolvedValueResolver = $placeholderResolver->getValueResolver($placeholder);

        // Assert
        $this->assertInstanceOf(ConfigurableValueResolverInterface::class, $resolvedValueResolver);
    }

    /**
     * @return void
     */
    public function testResolvePlaceholdersShouldReturnIndexedResolvedValues(): void
    {
        // Arrange
        $expectedSettingKey = static::PLACEHOLDER_NAME;
        $expectedValue = 'some_setting_value';
        $expectedSettings = [
            $expectedSettingKey => $expectedValue,
        ];

        $placeholder = $this->tester->createPlaceholder(
            $expectedSettingKey,
            static::VALUE_RESOLVER_ID,
            false,
        );

        $settingRepositoryMock = $this->createSettingRepositoryMock($expectedSettings);
        $valueResolverMock = $this->createMock(ValueResolverInterface::class);
        $valueResolverMock->expects($this->once())
            ->method('getSettingPaths')
            ->willReturn(array_keys($expectedSettings));
        $valueResolverMock->expects($this->once())
            ->method('getValue')
            ->willReturnCallback(function (ContextInterface $context) use ($expectedSettingKey): mixed {
                $this->assertArrayHasKey($expectedSettingKey, $context->getResolvedValues());

                return $context->getResolvedValues()[$expectedSettingKey];
            });

        $registryMock = $this->createRegistryMock($valueResolverMock);

        $context = new Context();
        $context->setResolvedValues($expectedSettings);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);

        // Act
        $result = $placeholderResolver->resolvePlaceholders([$placeholder], $context);

        // Assert
        $this->assertSame($expectedSettings, $result);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function providePlaceholders(): array
    {
        return [
            [
                static::VALUE => 'expectedValue',
                static::SETTINGS => [],
                static::OPTIONAL => true,
            ], [
                static::VALUE => 1,
                static::SETTINGS => [],
                static::OPTIONAL => true,
            ], [
                static::VALUE => true,
                static::SETTINGS => [],
                static::OPTIONAL => true,
            ], [
                static::VALUE => 2.0,
                static::SETTINGS => [],
                static::OPTIONAL => true,
            ],
        ];
    }

    /**
     * @param array $expectedSettings
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected function createSettingRepositoryMock(array $expectedSettings): ProjectSettingRepositoryInterface
    {
        $settingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $settingRepositoryMock->expects(empty($expectedSettings) ? $this->never() : $this->exactly(count($expectedSettings)))
            ->method('findOneByPath')
            ->willReturnCallback(function (string $settingPath) use ($expectedSettings): SettingInterface {
                return new Setting(
                    $settingPath,
                    $expectedSettings[$settingPath] ?? null,
                    SettingInterface::STRATEGY_REPLACE,
                );
            });

        return $settingRepositoryMock;
    }

    /**
     * @param array $expectedSettings
     * @param mixed $expectedValue
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface
     */
    protected function createValueResolverMock(array $expectedSettings, mixed $expectedValue): ValueResolverInterface
    {
        $valueResolverMock = $this->createMock(ValueResolverInterface::class);
        $valueResolverMock->expects($this->once())
            ->method('getSettingPaths')
            ->willReturn(array_keys($expectedSettings));
        $valueResolverMock->expects($this->once())
            ->method('getValue')
            ->willReturn($expectedValue);

        return $valueResolverMock;
    }

    /**
     * @param mixed $valueResolverMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface
     */
    protected function createRegistryMock(mixed $valueResolverMock): ValueResolverRegistryInterface
    {
        $registryMock = $this->createMock(ValueResolverRegistryInterface::class);
        $registryMock->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $registryMock->expects($this->once())
            ->method('get')
            ->willReturn($valueResolverMock);

        return $registryMock;
    }
}
