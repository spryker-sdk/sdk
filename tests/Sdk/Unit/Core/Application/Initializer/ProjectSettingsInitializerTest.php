<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Initializer;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializer;
use SprykerSdk\Sdk\Core\Application\Service\SettingManager;
use SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectSettingsInitializerProcessor;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Core
 * @group Application
 * @group Initializer
 * @group ProjectSettingsInitializerTest
 * Add your own group annotations below this line
 */
class ProjectSettingsInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testInitializeShouldInitializeWhenProjectSettingsPassed(): void
    {
        // Arrange
        $settingManager = $this->createSettingManagerMock();
        $settingManager->expects($this->once())->method('writeSettings');

        $projectSettingsInitializerProcessor = $this->createProjectSettingsInitializerProcessorMock();
        $projectSettingsInitializerProcessor->expects($this->once())->method('initialize');

        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createSettingRepositoryMock(),
            $settingManager,
            $projectSettingsInitializerProcessor,
        );

        $projectSettingsDto = new ProjectSettingsInitDto(['setting' => 'value'], false);

        // Act
        $projectSettingsInitializer->initialize($projectSettingsDto);
    }

    /**
     * @return void
     */
    public function testIsProjectSettingsInitialisedShouldCheckIsInitialized(): void
    {
        // Arrange
        $projectSettingsInitializerProcessor = $this->createProjectSettingsInitializerProcessorMock();
        $projectSettingsInitializerProcessor
            ->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);

        $projectSettingsInitializer = new ProjectSettingsInitializer(
            $this->createSettingRepositoryMock(),
            $this->createSettingManagerMock(),
            $projectSettingsInitializerProcessor,
        );

        $projectSettingsDto = new ProjectSettingsInitDto(['setting' => 'value'], false);

        // Act
        $isInitialized = $projectSettingsInitializer->isProjectSettingsInitialised($projectSettingsDto);

        // Assert
        $this->assertTrue($isInitialized);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected function createSettingRepositoryMock(): SettingRepositoryInterface
    {
        $settingRepositoryMock = $this->createMock(SettingRepositoryInterface::class);

        $settingRepositoryMock->method('findProjectSettings')->willReturn(
            [$this->createMock(SettingInterface::class)],
        );

        return $settingRepositoryMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Service\SettingManager|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSettingManagerMock(): SettingManager
    {
        return $this->createMock(SettingManager::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\ProjectSettingsInitializerProcessor|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProjectSettingsInitializerProcessorMock(): ProjectSettingsInitializerProcessor
    {
        return $this->createMock(ProjectSettingsInitializerProcessor::class);
    }
}
