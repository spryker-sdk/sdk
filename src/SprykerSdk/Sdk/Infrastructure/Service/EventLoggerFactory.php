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
use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;
use Throwable;

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
     * @return \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    public function createEventLogger(): EventLoggerInterface
    {
        return new EventLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogger(): LoggerInterface
    {
        $reportUsageStatistics = false;

        try {
            $reportUsageStatisticsSetting = $this->projectSettingRepository->findOneByPath('report_usage_statistics');

            if ($reportUsageStatisticsSetting) {
                $reportUsageStatistics = (bool)$reportUsageStatisticsSetting->getValues();
            }
            $projectDirSetting = $this->projectSettingRepository->findOneByPath('project_dir');

            if ($reportUsageStatistics && $projectDirSetting) {
                return $this->createFileLogger($projectDirSetting);
            }

            return $this->createNullLogger();
        } catch (Throwable $exception) {
            //When the SDK is not initialized settings can't be loaded from the DB in this case a file logger can't be configured
            return $this->createNullLogger();
        }
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter
     */
    protected function createJsonFormatter(): JsonFormatter
    {
        return new JsonFormatter();
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $projectDirSetting
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createFileLogger(SettingInterface $projectDirSetting): LoggerInterface
    {
        $logger = new Logger('event_logger');
        $logger->pushHandler($this->createFileHandler($projectDirSetting));

        return $logger;
    }

    /**
     * @return \Monolog\Logger
     */
    protected function createNullLogger(): Logger
    {
        $logger = new Logger('event_logger');
        $logger->pushHandler($this->createNullHandler());

        return $logger;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $projectDirSetting
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createFileHandler(SettingInterface $projectDirSetting): HandlerInterface
    {
        $handler = new StreamHandler($projectDirSetting->getValues() . '/.ssdk.log');
        $handler->setFormatter($this->createJsonFormatter());

        return $handler;
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createNullHandler(): HandlerInterface
    {
        return new NullHandler();
    }
}
