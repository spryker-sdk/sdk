<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\Setting;

use ArrayIterator;
use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingChoicesProviderRegistry;

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
    public function testRegistryThrowsExceptionWhenServiceNotFound(): void
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
    public function testRegistryCanCheckAndGenWhenServicesSetAsArray(): void
    {
        // Arrange
        $choiceProviderMock = $this->createChoicesProviderMock();
        $settingChoicesProviderRegistry = new SettingChoicesProviderRegistry(
            ['test-provider' => $choiceProviderMock],
        );

        // Act
        $isChoiceProviderSet = $settingChoicesProviderRegistry->hasSettingChoicesProvider('test-provider');
        $choiceProvider = $settingChoicesProviderRegistry->getSettingChoicesProvider('test-provider');

        // Assert
        $this->assertTrue($isChoiceProviderSet);
        $this->assertSame($choiceProviderMock, $choiceProvider);
    }

    /**
     * @return void
     */
    public function testRegistryCanCheckAndGenWhenServicesSetAsIterator(): void
    {
        // Arrange
        $choiceProviderMock = $this->createChoicesProviderMock();
        $settingChoicesProviderRegistry = new SettingChoicesProviderRegistry(
            new ArrayIterator(['test-provider' => $choiceProviderMock]),
        );

        // Act
        $isChoiceProviderSet = $settingChoicesProviderRegistry->hasSettingChoicesProvider('test-provider');
        $choiceProvider = $settingChoicesProviderRegistry->getSettingChoicesProvider('test-provider');

        // Assert
        $this->assertTrue($isChoiceProviderSet);
        $this->assertSame($choiceProviderMock, $choiceProvider);
    }

    /**
     * @return \SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface
     */
    protected function createChoicesProviderMock(): SettingChoicesProviderInterface
    {
        return $this->createMock(SettingChoicesProviderInterface::class);
    }
}
