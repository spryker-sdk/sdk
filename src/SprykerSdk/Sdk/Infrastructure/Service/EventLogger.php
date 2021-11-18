<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\EventLoggerInterface;
use SprykerSdk\Sdk\Core\Events\Event;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;

class EventLogger implements EventLoggerInterface
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {}

    /**
     * @param \SprykerSdk\Sdk\Core\Events\Event $event
     * @return void
     */
    public function logEvent(Event $event): void
    {
        $this->logger->info('', [JsonFormatter::CONTEXT_EVENT => $event]);
    }
}