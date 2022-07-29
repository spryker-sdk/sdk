<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Infrastructure\Logger\JsonFormatter;
use SprykerSdk\SdkContracts\Event\EventInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class EventLogger implements EventLoggerInterface
{
    protected LoggerInterface $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Event\Event $event
     *
     * @return void
     */
    public function logEvent(EventInterface $event): void
    {
        $this->logger->info('', [JsonFormatter::CONTEXT_EVENT => $event]);
    }
}
