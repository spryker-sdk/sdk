<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\AfterCommandExecutedAction;

use SprykerSdk\Sdk\Core\Application\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface;
use SprykerSdk\Sdk\Core\Domain\Event\TaskExecutedEvent;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class LogEventAction implements AfterCommandExecutedActionInterface
{
    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param \SprykerSdk\SdkContracts\Logger\EventLoggerInterface $eventLogger
     */
    public function __construct(EventLoggerInterface $eventLogger)
    {
        $this->eventLogger = $eventLogger;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        $this->eventLogger->logEvent(new TaskExecutedEvent($context->getTask(), $command, (bool)$context->getExitCode()));

        return $context;
    }
}
