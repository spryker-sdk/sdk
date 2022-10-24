<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

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
    public function beforeConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->setUpInputOutput($event->getInput(), $event->getOutput());
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $this->setUpInputOutput(new ArrayInput([]), new ConsoleOutput()); // todo :: provide input params from request.
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function setUpInputOutput(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->inputOutputConnectors as $inputOutputConnector) {
            $inputOutputConnector->setInput($input);
            $inputOutputConnector->setOutput($output);
        }
    }
}
