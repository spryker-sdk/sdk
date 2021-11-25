<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliRunnerSetupListener
{
    public function __construct(
        protected LocalCliRunner $cliRunner,
    ) {}

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        $this->cliRunner->setOutput($event->getOutput());
        $this->cliRunner->setHelperSet($event->getCommand()->getHelperSet() ?? $event->getCommand()->getApplication()->getHelperSet());
    }
}
