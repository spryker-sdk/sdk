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
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;
use Throwable;

class EventLoggerFactory
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var string
     */
    protected string $projectSettingsFile;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param string $projectSettingsFile
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        string $projectSettingsFile,
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->projectSettingsFile = $projectSettingsFile;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
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

            if ($reportUsageStatistics) {
                return $this->createFileLogger();
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
     * @return \Psr\Log\LoggerInterface
     */
    protected function createFileLogger(): LoggerInterface
    {
        $logger = new Logger('event_logger');
        $logger->pushHandler($this->createFileHandler());

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
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createFileHandler(): HandlerInterface
    {
        $handler = new StreamHandler(dirname($this->projectSettingsFile) . '/.ssdk.log');
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
