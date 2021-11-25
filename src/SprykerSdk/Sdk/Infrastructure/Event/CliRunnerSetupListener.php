<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliRunnerSetupListener
{
    protected LocalCliRunner $cliRunner;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner $cliRunner
     */
    public function __construct(LocalCliRunner $cliRunner)
    {
        $this->cliRunner = $cliRunner;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        $this->cliRunner->setOutput($event->getOutput());
        $this->cliRunner->setHelperSet($event->getCommand()->getHelperSet() ?? $event->getCommand()->getApplication()->getHelperSet());
    }
}
