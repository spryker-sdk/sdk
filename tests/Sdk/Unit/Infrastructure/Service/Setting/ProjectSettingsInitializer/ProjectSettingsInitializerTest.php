<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\Setting\ProjectSettingsInitializer;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Enum\Setting as SettingEnum;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectFilesInitializer;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectSettingsInitializer;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingInitializerRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group Setting
 * @group ProjectSettingsInitializer
 * @group ProjectSettingsInitializerTest
 */
class ProjectSettingsInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitializeShouldReturnEmptyArrayWhenValueHasNoInitialization(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(),
            $this->createSettingValueQuestionMock(),
            $this->createSettingInitializerRegistry(),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting('setting_path', []);

        $projectSettingsInitDto = new ProjectSettingsInitDto(['setting_path' => 'some_value'], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(0, $settings);
    }

    /**
     * @return void
     */
    public function testInitializeShouldReturnEmptyArrayWhenSettingIsUuidWithoutInitializer(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(),
            $this->createSettingValueQuestionMock(),
            $this->createSettingInitializerRegistry(['uuid_initializer' => $this->createSettingInitializerMock()]),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting(
            'setting_path',
            [],
            SettingInterface::STRATEGY_REPLACE,
            ValueTypeEnum::TYPE_UUID,
            SettingEnum::SETTING_TYPE_LOCAL,
            false,
            null,
            'uuid_initializer',
        );

        $projectSettingsInitDto = new ProjectSettingsInitDto(['setting_path' => 'val'], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(0, $settings);
    }

    /**
     * @return void
     */
    public function testInitializeShouldReturnEmptyArrayWhenAnswerIsToLeaveDefaultValues(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(false, true),
            $this->createSettingValueQuestionMock(),
            $this->createSettingInitializerRegistry(),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting(
            'setting_path',
            [],
            SettingInterface::STRATEGY_REPLACE,
            ValueTypeEnum::TYPE_UUID,
            SettingEnum::SETTING_TYPE_LOCAL,
            true,
        );

        $projectSettingsInitDto = new ProjectSettingsInitDto([], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(0, $settings);
    }

    /**
     * @return void
     */
    public function testInitializeShouldAskValueWhenValuesNotSet(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(true, true),
            $this->createSettingValueQuestionMock('setting_value', true),
            $this->createSettingInitializerRegistry(),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting(
            'setting_path',
            [],
            SettingInterface::STRATEGY_REPLACE,
            ValueTypeEnum::TYPE_UUID,
            SettingEnum::SETTING_TYPE_LOCAL,
            true,
        );

        $projectSettingsInitDto = new ProjectSettingsInitDto([], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(1, $settings);
        $this->assertSame('setting_value', $settings[0]->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeShouldReturnSettingsWhenValueSetInIncomingData(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(),
            $this->createSettingValueQuestionMock(),
            $this->createSettingInitializerRegistry(),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting(
            'setting_path',
            [],
            SettingInterface::STRATEGY_REPLACE,
            ValueTypeEnum::TYPE_UUID,
            SettingEnum::SETTING_TYPE_LOCAL,
            true,
        );

        $projectSettingsInitDto = new ProjectSettingsInitDto(['setting_path' => 'setting_value'], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(1, $settings);
        $this->assertSame('setting_value', $settings[0]->getValues());
    }

    /**
     * @return void
     */
    public function testInitializeShouldReturnEmptyArrayWhenValueNotChanged(): void
    {
        // Arrange
        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createChangeDefaultValueQuestionMock(),
            $this->createSettingValueQuestionMock(),
            $this->createSettingInitializerRegistry(),
            $this->createProjectFilesInitializerMock(),
        );

        $hasNoInitializationSetting = new Setting(
            'setting_path',
            'setting_value',
            SettingInterface::STRATEGY_REPLACE,
            ValueTypeEnum::TYPE_UUID,
            SettingEnum::SETTING_TYPE_LOCAL,
            true,
        );

        $projectSettingsInitDto = new ProjectSettingsInitDto(['setting_path' => 'setting_value'], false);

        // Act
        $settings = $projectSettingsInitializer->initialize([$hasNoInitializationSetting], $projectSettingsInitDto);

        // Assert
        $this->assertCount(0, $settings);
    }

    /**
     * @param bool $answer
     * @param bool $shouldAsk
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\ChangeDefaultValueQuestion
     */
    protected function createChangeDefaultValueQuestionMock(bool $answer = true, bool $shouldAsk = false): ChangeDefaultValueQuestion
    {
        $changeDefaultValueQuestionMock = $this->createMock(ChangeDefaultValueQuestion::class);

        $changeDefaultValueQuestionMock
            ->expects($shouldAsk ? $this->once() : $this->never())
            ->method('ask')
            ->willReturn($answer);

        return $changeDefaultValueQuestionMock;
    }

    /**
     * @param mixed $value
     * @param bool $shouldAsk
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question\SettingValueQuestion
     */
    protected function createSettingValueQuestionMock($value = '', bool $shouldAsk = false): SettingValueQuestion
    {
        $settingValueQuestionMock = $this->createMock(SettingValueQuestion::class);

        $settingValueQuestionMock
           ->expects($shouldAsk ? $this->once() : $this->never())
           ->method('ask')
           ->willReturn($value);

        return $settingValueQuestionMock;
    }

    /**
     * @param array $initializers
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingInitializerRegistry
     */
    public function createSettingInitializerRegistry(array $initializers = []): SettingInitializerRegistry
    {
        return new SettingInitializerRegistry($initializers);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Setting\SettingInitializerInterface
     */
    protected function createSettingInitializerMock(): SettingInitializerInterface
    {
        return $this->createMock(SettingInitializerInterface::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\ProjectFilesInitializer
     */
    protected function createProjectFilesInitializerMock(): ProjectFilesInitializer
    {
        return $this->createMock(ProjectFilesInitializer::class);
    }
}
