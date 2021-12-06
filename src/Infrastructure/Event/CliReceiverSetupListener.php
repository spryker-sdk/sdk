<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliReceiverSetupListener
{
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     */
    public function __construct(CliValueReceiver $cliValueReceiver)
    {
        $this->cliValueReceiver = $cliValueReceiver;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        $this->cliValueReceiver->setInput($event->getInput());
        $this->cliValueReceiver->setOutput($event->getOutput());
    }
}
