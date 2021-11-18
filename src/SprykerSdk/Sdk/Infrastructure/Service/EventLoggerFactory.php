<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;

class EventLoggerFactory
{
    public function __construct(
        protected ProjectSettingRepositoryInterface $projectSettingRepository
    ) {}

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface
     */
    public function createEventLogger(): EventLoggerInterface
    {
        $logger = new Logger('event_logger');
        $logger->pushHandler($this->createHandler());

        return new EventLogger($logger);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createHandler(): HandlerInterface
    {
        $reportUsageStatisticsSetting = $this->projectSettingRepository->findOneByPath('report_usage_statistics');
        $reportUsageStatistics = false;

        if ($reportUsageStatisticsSetting) {
            $reportUsageStatistics = (bool)$reportUsageStatisticsSetting->values;
        }

        $handler = new NullHandler();

        $projectDirSetting = $this->projectSettingRepository->findOneByPath('project_dir');

        if ($reportUsageStatistics && $projectDirSetting) {
            $handler = new StreamHandler((string)$projectDirSetting->values . '/.ssdk.log');
            $handler->setFormatter(new JsonFormatter());
        }

        return $handler;
    }
}