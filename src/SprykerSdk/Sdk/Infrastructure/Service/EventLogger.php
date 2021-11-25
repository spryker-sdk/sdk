<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Contracts\Events\EventInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;

class EventLogger implements EventLoggerInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        protected LoggerInterface $logger,
    ) {}

    /**
     * @param \SprykerSdk\Sdk\Contracts\Events\EventInterface $event
     *
     * @return void
     */
    public function logEvent(EventInterface $event): void
    {
        $this->logger->info('', [JsonFormatter::CONTEXT_EVENT => $event]);
    }
}
