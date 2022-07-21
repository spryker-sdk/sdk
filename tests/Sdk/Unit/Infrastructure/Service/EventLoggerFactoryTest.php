<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Service\EventLogger;
use SprykerSdk\Sdk\Infrastructure\Service\EventLoggerFactory;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class EventLoggerFactoryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
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
            ->expects($this->exactly(2))
            ->method('findOneByPath')
            ->willReturn($this->createMock(SettingInterface::class));
        $eventLogger = new EventLoggerFactory($this->projectSettingRepository);

        // Act
        $logger = $eventLogger->createEventLogger();

        // Assert
        $this->assertInstanceOf(EventLogger::class, $logger);
    }
}
