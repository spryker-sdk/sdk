<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Setting;

use ArrayIterator;
use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingChoicesProviderRegistry;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group Setting
 * @group SettingChoicesProviderRegistryTest
 */
class SettingChoicesProviderRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSettingChoicesProviderShouldThrowExceptionWhenServiceNotFound(): void
    {
        // Arrange
        $settingChoicesProviderRegistry = new SettingChoicesProviderRegistry([]);
        $this->expectException(InvalidArgumentException::class);

        // Act
        $settingChoicesProviderRegistry->getSettingChoicesProvider('non-existent');
    }

    /**
     * @return void
     */
    public function testGetSettingChoicesProviderShouldCheckAndGetWhenServicesSetAsArray(): void
    {
        // Arrange
        $choicesProviderMock = $this->createChoicesProviderMock();
        $settingChoicesProviderRegistry = new SettingChoicesProviderRegistry(
            ['test-provider' => $choicesProviderMock],
        );

        // Act
        $isChoicesProviderSet = $settingChoicesProviderRegistry->hasSettingChoicesProvider('test-provider');
        $choicesProvider = $settingChoicesProviderRegistry->getSettingChoicesProvider('test-provider');

        // Assert
        $this->assertTrue($isChoicesProviderSet);
        $this->assertSame($choicesProviderMock, $choicesProvider);
    }

    /**
     * @return void
     */
    public function testGetSettingChoicesProviderShouldCheckAndGetWhenServicesSetAsIterator(): void
    {
        // Arrange
        $choicesProviderMock = $this->createChoicesProviderMock();
        $settingChoicesProviderRegistry = new SettingChoicesProviderRegistry(
            new ArrayIterator(['test-provider' => $choicesProviderMock]),
        );

        // Act
        $isChoiceProviderSet = $settingChoicesProviderRegistry->hasSettingChoicesProvider('test-provider');
        $choiceProvider = $settingChoicesProviderRegistry->getSettingChoicesProvider('test-provider');

        // Assert
        $this->assertTrue($isChoiceProviderSet);
        $this->assertSame($choicesProviderMock, $choiceProvider);
    }

    /**
     * @return \SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface
     */
    protected function createChoicesProviderMock(): SettingChoicesProviderInterface
    {
        return $this->createMock(SettingChoicesProviderInterface::class);
    }
}
