<?php

namespace Sdk\Logger;

use Sdk\Transfer\TaskLogTransfer;

interface LoggerInterface
{
    /**
     * @param \Sdk\Transfer\TaskLogTransfer $taskLogTransfer
     *
     * @return void
     */
    public function log(TaskLogTransfer $taskLogTransfer): void;
}
