<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use DateTimeInterface;
use JetBrains\PhpStorm\ArrayShape;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use SprykerSdk\Sdk\Contracts\Events\EventInterface;

class JsonFormatter extends MonologJsonFormatter
{
    public const CONTEXT_EVENT = 'event';

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
    #[ArrayShape(['id' => "string", 'type' => "string", 'event' => "string", 'successful' => "bool", 'triggered_by' => "string", 'sdkContext' => "string"])] protected function transformEventToArray(EventInterface $event): array
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
