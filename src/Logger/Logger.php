<?php

namespace Sdk\Logger;

use Psr\Log\LoggerInterface;
use Sdk\Logger\Formatter\JsonFormatter;
use Sdk\Dto\TaskLogDto;

class Logger implements \Sdk\Logger\LoggerInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var bool
     */
    protected bool $reportUsageStatistics;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, bool $reportUsageStatistics)
    {
        $this->logger = $logger;
        $this->reportUsageStatistics = $reportUsageStatistics;
    }

    /**
     * @param \Sdk\Dto\TaskLogDto $taskLogTransfer
     *
     * @return void
     */
    public function log(TaskLogDto $taskLogTransfer): void
    {
        if ($this->reportUsageStatistics) {
            $this->logger->debug((string) $taskLogTransfer->getMessage(), [JsonFormatter::CONTEXT_TASK_LOG => $taskLogTransfer]);
        }
    }
}
