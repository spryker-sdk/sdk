<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliReceiverSetupListener
{
    public function __construct(
        protected CliValueReceiver $cliValueReceiver,
    ) {}

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        $this->cliValueReceiver->setInput($event->getInput());
        $this->cliValueReceiver->setOutput($event->getOutput());
    }
}