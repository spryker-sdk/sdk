<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Setting;

use ArrayIterator;
use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingChoicesProviderRegistry;
use SprykerSdk\SdkContracts\Setting\SettingChoicesProviderInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Setting
 * @group SettingChoicesProviderRegistryTest
 * Add your own group annotations below this line
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
     * @return \SprykerSdk\SdkContracts\Setting\SettingChoicesProviderInterface
     */
    protected function createChoicesProviderMock(): SettingChoicesProviderInterface
    {
        return $this->createMock(SettingChoicesProviderInterface::class);
    }
}
