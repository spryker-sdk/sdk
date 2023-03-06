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
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException;
use SprykerSdk\SdkContracts\Enum\Setting;

class ErrorLoggerFactory implements ErrorLoggerFactoryInterface
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'error_logger';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var string
     */
    protected string $projectLogFile;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected ?LoggerInterface $logger = null;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param string $projectLogFile
     */
    public function __construct(ProjectSettingRepositoryInterface $projectSettingRepository, string $projectLogFile)
    {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->projectLogFile = $projectLogFile;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getErrorLogger(): LoggerInterface
    {
        if ($this->logger === null) {
            $this->logger = new Logger(static::LOGGER_NAME);
            $this->logger->pushHandler($this->createLoggerHandler());
        }

        return $this->logger;
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createLoggerHandler(): HandlerInterface
    {
        try {
            $projectDirSetting = $this->projectSettingRepository->findOneByPath(Setting::PATH_PROJECT_DIR);
        } catch (SettingsNotInitializedException $e) {
            return new NullHandler();
        }

        if ($projectDirSetting === null) {
            return new NullHandler();
        }

        $handler = new StreamHandler($projectDirSetting->getValues() . DIRECTORY_SEPARATOR . $this->projectLogFile);
        $handler->setFormatter(new JsonFormatter());

        return $handler;
    }
}
