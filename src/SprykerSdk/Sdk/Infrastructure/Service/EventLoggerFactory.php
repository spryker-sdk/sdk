<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;

class EventLoggerFactory
{
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface
     */
    public function createEventLogger(): EventLoggerInterface
    {
        return new EventLogger($this->createLogger());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createHandler(): HandlerInterface
    {
        $reportUsageStatisticsSetting = $this->projectSettingRepository->findOneByPath('report_usage_statistics');
        $reportUsageStatistics = false;

        if ($reportUsageStatisticsSetting) {
            $reportUsageStatistics = (bool)$reportUsageStatisticsSetting->getValues();
        }

        $handler = new NullHandler();

        $projectDirSetting = $this->projectSettingRepository->findOneByPath('project_dir');

        if ($reportUsageStatistics && $projectDirSetting) {
            $handler = $this->createFileLogger($projectDirSetting);
        }

        return $handler;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter
     */
    protected function createJsonFormatter(): JsonFormatter
    {
        return new JsonFormatter();
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface $projectDirSetting
     *
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createFileLogger(SettingInterface $projectDirSetting): StreamHandler
    {
        $handler = new StreamHandler($projectDirSetting->getValues() . '/.ssdk.log');
        $handler->setFormatter($this->createJsonFormatter());

        return $handler;
    }

    /**
     * @return \Monolog\Logger
     */
    protected function createLogger(): Logger
    {
        $logger = new Logger('event_logger');
        $logger->pushHandler($this->createHandler());

        return $logger;
    }
}
