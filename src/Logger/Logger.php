<?php

namespace Sdk\Logger;

use Psr\Log\LoggerInterface;
use Sdk\Logger\Formatter\JsonFormatter;
use Sdk\Transfer\TaskLogTransfer;

class Logger
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
     * @param \Sdk\Transfer\TaskLogTransfer $taskLogTransfer
     *
     * @return void
     */
    public function log(TaskLogTransfer $taskLogTransfer): void
    {
        if ($this->reportUsageStatistics) {
            $this->logger->debug('', [JsonFormatter::CONTEXT_TASK_LOG => $taskLogTransfer]);
        }
    }
}
