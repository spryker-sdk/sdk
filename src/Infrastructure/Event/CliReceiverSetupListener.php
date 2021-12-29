<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliReceiverSetupListener
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface>
     */
    protected iterable $inputOutputConnectors;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface> $inputOutputConnectors
     */
    public function __construct(iterable $inputOutputConnectors)
    {
        $this->inputOutputConnectors = $inputOutputConnectors;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event)
    {
        foreach ($this->inputOutputConnectors as $inputOutputConnector) {
            $inputOutputConnector->setInput($event->getInput());
            $inputOutputConnector->setOutput($event->getOutput());
        }
    }
}
