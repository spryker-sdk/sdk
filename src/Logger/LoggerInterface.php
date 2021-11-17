<?php

namespace Sdk\Logger;

use Sdk\Dto\TaskLogDto;

interface LoggerInterface
{
    /**
     * @param \Sdk\Dto\TaskLogDto $taskLogTransfer
     *
     * @return void
     */
    public function log(TaskLogDto $taskLogTransfer): void;
}
