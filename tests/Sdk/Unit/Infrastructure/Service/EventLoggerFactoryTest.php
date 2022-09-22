<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Service\EventLoggerFactory;
use SprykerSdk\Sdk\Infrastructure\Service\Logger\EventLogger;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class EventLoggerFactoryTest extends Unit
{
    /**
     * @var string
     */
    protected const PROJECT_SETTINGS_FILE = '.ssdk';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->projectSettingRepository = $this->createMock(ProjectSettingRepositoryInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testCreateStreamHandler(): void
    {
        // Arrange
        $this->projectSettingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->willReturn($this->createMock(SettingInterface::class));
        $eventLogger = new EventLoggerFactory($this->projectSettingRepository, static::PROJECT_SETTINGS_FILE);

        // Act
        $logger = $eventLogger->createEventLogger();

        // Assert
        $this->assertInstanceOf(EventLogger::class, $logger);
    }
}
