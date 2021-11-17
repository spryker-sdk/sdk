<?php

namespace Sdk\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Sdk\Logger\Formatter\JsonFormatter;

class LoggerFactory
{
    /**
     * @param string $logFilePath
     * @param bool $reportUsageStatistics
     *
     * @return \Sdk\Logger\Logger
     */
    public function createLogger(string $logFilePath, bool $reportUsageStatistics): Logger
    {
        return new Logger(
            $this->createMonologLogger($logFilePath),
            $reportUsageStatistics
        );
    }

    /**
     * @param string $logFilePath
     *
     * @return \Monolog\Logger
     */
    protected function createMonologLogger(string $logFilePath): MonologLogger
    {
        $logger = new MonologLogger('json-logger');
        $logger->pushHandler($this->createStreamHandler($logFilePath));

        return $logger;
    }

    /**
     * @return \Sdk\Logger\Formatter\JsonFormatter
     */
    protected function createJsonFormatter(): JsonFormatter
    {
        return new JsonFormatter();
    }

    /**
     * @param string $stream
     *
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createStreamHandler(string $stream): StreamHandler
    {
        $streamHandler = new StreamHandler($stream);
        $streamHandler->setFormatter($this->createJsonFormatter());

        return $streamHandler;
    }
}
