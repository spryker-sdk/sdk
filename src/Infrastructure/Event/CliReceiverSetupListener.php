<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Infrastructure\Service\FilesystemInitInterface;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ApiInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessorReceiverInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CliReceiverSetupListener
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Event\ReceiverInterface>
     */
    protected iterable $inputOutputConnectors;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ApiInteractionProcessor
     */
    protected ApiInteractionProcessor $apiInteractionProcessor;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor
     */
    protected CliInteractionProcessor $cliInteractionProcessor;

    /**
     * @var string
     */
    protected string $sdkPath;

    /**
     * @param iterable $inputOutputConnectors
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ApiInteractionProcessor $apiInteractionProcessor
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor $cliInteractionProcessor
     * @param string $sdkPath
     */
    public function __construct(
        iterable $inputOutputConnectors,
        ApiInteractionProcessor $apiInteractionProcessor,
        CliInteractionProcessor $cliInteractionProcessor,
        string $sdkPath
    ) {
        $this->inputOutputConnectors = $inputOutputConnectors;
        $this->apiInteractionProcessor = $apiInteractionProcessor;
        $this->cliInteractionProcessor = $cliInteractionProcessor;
        $this->sdkPath = $sdkPath;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event): void
    {
        if (!$event->getCommand()) {
            return;
        }

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

            if ($inputOutputConnector instanceof CommandReceiverInterface) {
                /** @var \Symfony\Component\Console\Helper\HelperSet $helperSet */
                $helperSet = $event->getCommand()->getHelperSet() ?? $this->getApplicationHelperSet($event);
                $inputOutputConnector->setHelperSet($helperSet);
            }

            if ($inputOutputConnector instanceof InteractionProcessorReceiverInterface) {
                $inputOutputConnector->setInteractionProcessor($this->cliInteractionProcessor);
            }

            if ($inputOutputConnector instanceof FilesystemInitInterface) {
                $inputOutputConnector->setcwd((string)getcwd());
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        foreach ($this->inputOutputConnectors as $inputOutputConnector) {
            if ($inputOutputConnector instanceof OutputReceiverInterface) {
                $inputOutputConnector->setOutput(new BufferedOutput());
            }

            if ($inputOutputConnector instanceof RequestDataReceiverInterface) {
                $inputOutputConnector->setRequestData($event->getRequest()->request->all());
            }

            if ($inputOutputConnector instanceof CommandReceiverInterface) {
                $inputOutputConnector->setHelperSet(new HelperSet([
                    new FormatterHelper(),
                    new DebugFormatterHelper(),
                    new ProcessHelper(),
                    new QuestionHelper(),
                ]));
            }

            if ($inputOutputConnector instanceof InteractionProcessorReceiverInterface) {
                $inputOutputConnector->setInteractionProcessor($this->apiInteractionProcessor);
            }

            if ($inputOutputConnector instanceof FilesystemInitInterface) {
                $inputOutputConnector->setcwd($this->sdkPath);
            }
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
