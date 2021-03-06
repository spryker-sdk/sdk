<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Helper\HelperSet;

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
        if (!$event->getCommand()) {
            return;
        }

        $this->cliRunner->setOutput($event->getOutput());

        $helperSet = $event->getCommand()->getHelperSet() ?? $this->getApplicationHelperSet($event);

        if ($helperSet) {
            $this->cliRunner->setHelperSet($helperSet);
        }
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return \Symfony\Component\Console\Helper\HelperSet|null
     */
    protected function getApplicationHelperSet(ConsoleCommandEvent $event): ?HelperSet
    {
        if (
            $event->getCommand() !== null
            && $event->getCommand()->getApplication() !== null
            && $event->getCommand()->getApplication()->getHelperSet() !== null
        ) {
            return $event->getCommand()->getApplication()->getHelperSet();
        }

        return null;
    }
}
