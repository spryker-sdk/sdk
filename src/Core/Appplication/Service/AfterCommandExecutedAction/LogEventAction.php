<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\AfterCommandExecutedAction;

use SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction\AfterCommandExecutedActionInterface;
use SprykerSdk\Sdk\Core\Domain\Events\TaskExecutedEvent;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
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
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context, TaskInterface $task): ContextInterface
    {
        $this->eventLogger->logEvent(new TaskExecutedEvent($task, $command, (bool)$context->getExitCode()));

        return $context;
    }
}
