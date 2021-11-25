<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;

class PlaceholderResolverTest extends Unit
{
    /**
     * @var string
     */
    protected const VALUE_RESOLVER_ID = 'value_resolver_id';

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
        $placeholderMock = $this->createPlaceholderMock($optionalPlaceholder);
        $settingRepositoryMock = $this->createSettingRepositoryMock($expectedSettings);
        $valueResolverMock = $this->createValueResolverMock($expectedSettings, $expectedValue);
        $registryMock = $this->createRegistryMock($valueResolverMock);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);
        $this->assertSame($expectedValue, $placeholderResolver->resolve($placeholderMock));
    }

    /**
     * @return void
     */
    public function testSettingsAreInjectedDuringResolve(): void
    {
        $expectedSettingKey = 'test_setting';
        $expectedValue = 'some_setting_value';
        $expectedSettings = [
             $expectedSettingKey => $expectedValue,
        ];

        $placeholderMock = $this->createPlaceholderMock(true);
        $settingRepositoryMock = $this->createSettingRepositoryMock($expectedSettings);
        $valueResolverMock = $this->createMock(ValueResolverInterface::class);
        $valueResolverMock->expects($this->once())
            ->method('getSettingPaths')
            ->willReturn(array_keys($expectedSettings));
        $valueResolverMock->expects($this->once())
            ->method('getValue')
            ->willReturnCallback(function (array $settings) use ($expectedSettingKey): mixed {
                $this->assertArrayHasKey($expectedSettingKey, $settings);

                return $settings[$expectedSettingKey];
            });
        $registryMock = $this->createRegistryMock($valueResolverMock);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);
        $this->assertSame($expectedValue, $placeholderResolver->resolve($placeholderMock));
    }

    /**
     * @return void
     */
    public function testUnresolvableValueResolver(): void
    {
        $settingRepositoryMock = $this->createSettingRepositoryMock([]);
        $registryMock = $this->createMock(ValueResolverRegistryInterface::class);
        $registryMock->expects($this->once())
            ->method('has')
            ->willReturn(false);
        $registryMock->expects($this->never())
            ->method('get')
            ->willReturn(null);
        $placeholderMock = $this->createMock(PlaceholderInterface::class);
        $placeholderMock->expects($this->exactly(2))
            ->method('getValueResolver')
            ->willReturn(static::VALUE_RESOLVER_ID);

        $this->expectException(UnresolvablePlaceholderException::class);
        $this->expectExceptionMessage('Placeholder not resolvable ' . static::VALUE_RESOLVER_ID);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);
        $placeholderResolver->getValueResolver($placeholderMock);
    }

    /**
     * @return void
     */
    public function testConfigurableValueResolver(): void
    {
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
        $placeholderMock = $this->createMock(PlaceholderInterface::class);
        $placeholderMock->expects($this->exactly(2))
            ->method('getValueResolver')
            ->willReturn(static::VALUE_RESOLVER_ID);
        $placeholderMock->expects($this->once())
            ->method('getConfiguration')
            ->willReturn($expectedConfiguration);

        $placeholderResolver = new PlaceholderResolver($settingRepositoryMock, $registryMock);
        $resolvedValueResolver = $placeholderResolver->getValueResolver($placeholderMock);
        $this->assertInstanceOf(ConfigurableValueResolverInterface::class, $resolvedValueResolver);
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
    protected function createSettingRepositoryMock(array $expectedSettings): mixed
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
     * @param bool $optionalPlaceholder
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(bool $optionalPlaceholder): mixed
    {
        $placeholderMock = $this->createMock(PlaceholderInterface::class);
        $placeholderMock->expects($this->exactly(2))
            ->method('getValueResolver')
            ->willReturn(static::VALUE_RESOLVER_ID);
        $placeholderMock->expects($this->once())
            ->method('isOptional')
            ->willReturn($optionalPlaceholder);

        return $placeholderMock;
    }

    /**
     * @param array $expectedSettings
     * @param mixed $expectedValue
     *
     * @return mixed
     */
    protected function createValueResolverMock(array $expectedSettings, mixed $expectedValue): mixed
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
     * @return mixed
     */
    protected function createRegistryMock(mixed $valueResolverMock): mixed
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
