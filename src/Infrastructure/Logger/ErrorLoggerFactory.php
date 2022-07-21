<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\SettingsNotInitializedException;
use SprykerSdk\Sdk\Core\Domain\Enum\SettingPath;

class ErrorLoggerFactory
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'error_logger';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(ProjectSettingRepositoryInterface $projectSettingRepository)
    {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger(): LoggerInterface
    {
        $logger = new Logger(static::LOGGER_NAME);
        $logger->pushHandler($this->createLoggerHandler());

        return $logger;
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createLoggerHandler(): HandlerInterface
    {
        try {
            $projectDirSetting = $this->projectSettingRepository->findOneByPath(SettingPath::PROJECT_DIR);
        } catch (SettingsNotInitializedException $e) {
            return new NullHandler();
        }

        if ($projectDirSetting === null) {
            return new NullHandler();
        }

        $handler = new StreamHandler($projectDirSetting->getValues() . '/.ssdk.log');
        $handler->setFormatter(new JsonFormatter());

        return $handler;
    }
}
