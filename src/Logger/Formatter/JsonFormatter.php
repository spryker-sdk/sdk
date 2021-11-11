<?php

namespace Sdk\Logger\Formatter;

use DateTimeInterface;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Sdk\Transfer\TaskLogTransfer;

class JsonFormatter extends MonologJsonFormatter
{
    public const CONTEXT_TASK_LOG = 'taskLog';

    /**
     * @param int $batchMode
     * @param bool $appendNewline
     * @param bool $ignoreEmptyContextAndExtra
     */
    public function __construct(
        int $batchMode = MonologJsonFormatter::BATCH_MODE_JSON,
        bool $appendNewline = true,
        bool $ignoreEmptyContextAndExtra = true
    ) {
        parent::__construct($batchMode, $appendNewline, $ignoreEmptyContextAndExtra);
    }

    /**
     * @param array $record
     *
     * @return string
     */
    public function format(array $record): string
    {
        if (isset($record['datetime']) && $record['datetime'] instanceof DateTimeInterface) {
            $record['timestamp'] = $record['datetime']->getTimestamp();
            unset($record['datetime']);
        }

        if (isset($record['context'][static::CONTEXT_TASK_LOG]) && $record['context'][static::CONTEXT_TASK_LOG] instanceof TaskLogTransfer) {
            $record += $this->transformTaskLogTransferToArray($record['context'][static::CONTEXT_TASK_LOG]);
        }

        $record = $this->unsetRedundantFields($record);

        return parent::format($record);
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function unsetRedundantFields(array $record): array
    {
        unset(
            $record['context'],
            $record['level'],
            $record['level_name'],
            $record['channel'],
            $record['message'],
        );

        return $record;
    }

    /**
     * @param TaskLogTransfer $taskLogTransfer
     *
     * @return array
     */
    protected function transformTaskLogTransferToArray(TaskLogTransfer $taskLogTransfer): array
    {
        return [
            'id' => $taskLogTransfer->getId(),
            'type' => $taskLogTransfer->getType(),
            'event' => $taskLogTransfer->getEvent(),
            'successful' => $taskLogTransfer->getIsSuccessful(),
            'triggered_by' => $taskLogTransfer->getTriggeredBy(),
            'context' => $taskLogTransfer->getContext(),
        ];
    }
}
