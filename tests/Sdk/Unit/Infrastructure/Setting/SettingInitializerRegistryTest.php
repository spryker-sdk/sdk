<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Setting;

use ArrayIterator;
use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group Setting
 * @group SettingInitializerRegistryTest
 */
class SettingInitializerRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSettingInitializerShouldThrowsExceptionWhenServiceNotFound(): void
    {
        // Arrange
        $settingInitializerRegistry = new SettingInitializerRegistry([]);
        $this->expectException(InvalidArgumentException::class);

        // Act
        $settingInitializerRegistry->getSettingInitializer('non-existent');
    }

    /**
     * @return void
     */
    public function testGetSettingInitializerShouldCheckAndGenWhenServicesSetAsArray(): void
    {
        // Arrange
        $settingInitializerMockMock = $this->createSettingInitializerMock();
        $settingInitializerRegistry = new SettingInitializerRegistry(
            ['test-provider' => $settingInitializerMockMock],
        );

        // Act
        $isSettingInitializerSet = $settingInitializerRegistry->hasSettingInitializer('test-provider');
        $settingInitializer = $settingInitializerRegistry->getSettingInitializer('test-provider');

        // Assert
        $this->assertTrue($isSettingInitializerSet);
        $this->assertSame($settingInitializerMockMock, $settingInitializer);
    }

    /**
     * @return void
     */
    public function testGetSettingInitializerShouldCheckAndGenWhenServicesSetAsIterator(): void
    {
        // Arrange
        $settingInitializerMockMock = $this->createSettingInitializerMock();
        $settingInitializerRegistry = new SettingInitializerRegistry(
            new ArrayIterator(['test-provider' => $settingInitializerMockMock]),
        );

        // Act
        $isSettingInitializerSet = $settingInitializerRegistry->hasSettingInitializer('test-provider');
        $settingInitializer = $settingInitializerRegistry->getSettingInitializer('test-provider');

        // Assert
        $this->assertTrue($isSettingInitializerSet);
        $this->assertSame($settingInitializerMockMock, $settingInitializer);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Setting\SettingInitializerInterface
     */
    protected function createSettingInitializerMock(): SettingInitializerInterface
    {
        return $this->createMock(SettingInitializerInterface::class);
    }
}
