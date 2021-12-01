<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use SprykerSdk\Sdk\Infrastructure\Service\PhpCommandRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Helper\HelperSet;

class PhpCommandRunnerSetupListener
{
    protected PhpCommandRunner $phpCommandRunner;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\PhpCommandRunner $phpCommandRunner
     */
    public function __construct(PhpCommandRunner $phpCommandRunner)
    {
        $this->phpCommandRunner = $phpCommandRunner;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        if (!$event->getCommand()) {
            return;
        }

        $this->phpCommandRunner->setOutput($event->getOutput());
    }
}
