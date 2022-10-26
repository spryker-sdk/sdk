<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessor;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group InitializerTest
 * Add your own group annotations below this line
 */
class InitializerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessor
     */
    protected InteractionProcessor $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository
     */
    protected SettingRepository $settingRepository;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $optionSettingsPath;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface
     */
    protected TaskYamlFileLoaderInterface $taskYamlFileLoader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->taskYamlFileLoader = $this->createMock(TaskYamlFileLoaderInterface::class);
        $this->cliValueReceiver = $this->createMock(InteractionProcessor::class);
        $this->settingRepository = $this->createMock(SettingRepository::class);
        $this->taskManager = $this->createMock(TaskManagerInterface::class);

        $this->initializerService = new Initializer(
            $this->cliValueReceiver,
            $this->settingRepository,
            $this->taskManager,
            $this->taskYamlFileLoader,
        );
    }

    /**
     * @return void
     */
    public function testIfSettingComing(): void
    {
        // Arrange
        $optionSettings = ['testKey' => 'value'];
        $settings = [
            new Setting(
                'testKey',
                'testValue',
                'overwrite',
                'string',
                'sdk',
            ),
        ];
        $this->taskManager
            ->expects($this->once())
            ->method('initialize');

        $this->taskYamlFileLoader
            ->expects($this->once())
            ->method('loadAll')
            ->willReturn([]);
        $this->settingRepository
            ->expects($this->exactly(count($settings)))
            ->method('save');
        $this->settingRepository->expects($this->once())
            ->method('initSettingDefinition')
            ->willReturn($settings);

        // Act
        $this->initializerService->initialize($optionSettings);
    }

    /**
     * @return void
     */
    public function testDoesNotHasInitialization(): void
    {
        // Arrange
        $optionSettings = [];
        $settings = [
            new Setting(
                'testKey',
                'testValue',
                'overwrite',
                'string',
                'sdk',
                false,
            ),
        ];
        $this->taskManager->expects($this->once())
            ->method('initialize');
        $this->taskYamlFileLoader
            ->expects($this->once())
            ->method('loadAll')
            ->willReturn([]);
        $this->settingRepository
            ->expects($this->never())
            ->method('save');
        $this->settingRepository->expects($this->once())
            ->method('initSettingDefinition')
            ->willReturn($settings);

        // Act
        $this->initializerService->initialize($optionSettings);
    }

    /**
     * @return void
     */
    public function testValueNotInit(): void
    {
        // Arrange
        $optionSettings = [];
        $settings = [
            new Setting(
                'testKey',
                null,
                'overwrite',
                'string',
                'sdk',
                true,
            ),
        ];
        $this->taskManager->expects($this->once())
            ->method('initialize');
        $this->taskYamlFileLoader
            ->expects($this->once())
            ->method('loadAll')
            ->willReturn([]);
        $this->settingRepository
            ->expects($this->never())
            ->method('save');
        $this->settingRepository->expects($this->once())
            ->method('initSettingDefinition')
            ->willReturn($settings);
        $this->cliValueReceiver
            ->expects($this->exactly(count($settings)))
            ->method('receiveValue')
            ->willReturn(null);

        // Act
        $this->initializerService->initialize($optionSettings);
    }

    /**
     * @return void
     */
    public function testWithLocalProjectSetting(): void
    {
        // Arrange
        $optionSettings = [];
        $settings = [
            new Setting(
                'testKey',
                null,
                'overwrite',
                'string',
                'local',
                true,
            ),
        ];
        $this->taskManager->expects($this->once())
            ->method('initialize');
        $this->taskYamlFileLoader
            ->expects($this->once())
            ->method('loadAll')
            ->willReturn([]);
        $this->settingRepository
            ->expects($this->never())
            ->method('save');
        $this->settingRepository->expects($this->once())
            ->method('initSettingDefinition')
            ->willReturn($settings);
        $this->cliValueReceiver
            ->expects($this->never())
            ->method('receiveValue')
            ->willReturn(null);

        // Act
        $this->initializerService->initialize($optionSettings);
    }

    /**
     * @return void
     */
    public function testWithSharedProjectSetting(): void
    {
        // Arrange
        $optionSettings = [];
        $settings = [
            new Setting(
                'testKey',
                null,
                'overwrite',
                'string',
                'shared',
                true,
            ),
        ];
        $this->taskManager->expects($this->once())
            ->method('initialize');
        $this->taskYamlFileLoader
            ->expects($this->once())
            ->method('loadAll')
            ->willReturn([]);
        $this->settingRepository
            ->expects($this->never())
            ->method('save');
        $this->settingRepository->expects($this->once())
            ->method('initSettingDefinition')
            ->willReturn($settings);
        $this->cliValueReceiver
            ->expects($this->never())
            ->method('receiveValue')
            ->willReturn(null);

        // Act
        $this->initializerService->initialize($optionSettings);
    }
}
