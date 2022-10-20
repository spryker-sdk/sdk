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
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Event\InputReceiverInterface|\SprykerSdk\Sdk\Infrastructure\Event\OutputReceiverInterface>
     */
    protected iterable $inputOutputConnectors;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Event\InputReceiverInterface|\SprykerSdk\Sdk\Infrastructure\Event\OutputReceiverInterface> $inputOutputConnectors
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
            if ($inputOutputConnector instanceof InputReceiverInterface) {
                $inputOutputConnector->setInput($event->getInput());
            }

            if ($inputOutputConnector instanceof OutputReceiverInterface) {
                $inputOutputConnector->setOutput($event->getOutput());
            }

            if ($inputOutputConnector instanceof RequestDataReceiverInterface) {
                $inputOutputConnector->setRequestData($event->getInput()->getOptions());
            }
        }
    }
}
