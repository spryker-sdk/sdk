<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use DateTimeInterface;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use SprykerSdk\Sdk\Contracts\Events\EventInterface;

class JsonFormatter extends MonologJsonFormatter
{
    /**
     * @var string
     */
    public const CONTEXT_EVENT = 'event';

    /**
     * @param \Monolog\Formatter\JsonFormatter::BATCH_MODE_\*|int $batchMode
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

        if (isset($record['context'][static::CONTEXT_EVENT]) && $record['context'][static::CONTEXT_EVENT] instanceof EventInterface) {
            $record += $this->transformEventToArray($record['context'][static::CONTEXT_EVENT]);
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
     * @param \SprykerSdk\Sdk\Contracts\Events\EventInterface $event
     *
     * @return array
     */
    protected function transformEventToArray(EventInterface $event): array
    {
        return [
            'id' => $event->getId(),
            'type' => $event->getType(),
            'event' => $event->getEvent(),
            'successful' => $event->isSuccessful(),
            'triggered_by' => $event->getTriggeredBy(),
            'sdkContext' => $event->getContext(),
        ];
    }
}
