<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use DateTimeInterface;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use SprykerSdk\Sdk\Core\Events\TaskEvent;

class JsonFormatter extends MonologJsonFormatter
{
    public const CONTEXT_EVENT = 'taskLog';

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

        if (isset($record['context'][static::CONTEXT_EVENT]) && $record['context'][static::CONTEXT_EVENT] instanceof TaskEvent) {
            $record += $this->transformTaskLogTransferToArray($record['context'][static::CONTEXT_EVENT]);
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
        );

        return $record;
    }

    /**
     * @param TaskEvent $taskLogTransfer
     *
     * @return array
     */
    protected function transformTaskLogTransferToArray(TaskEvent $taskLogTransfer): array
    {
        return [
            'id' => $taskLogTransfer->id,
            'type' => $taskLogTransfer->type,
            'event' => $taskLogTransfer->event,
            'successful' => $taskLogTransfer->isSuccessful,
            'triggered_by' => $taskLogTransfer->triggeredBy,
            'sdkContext' => $taskLogTransfer->context,
        ];
    }
}