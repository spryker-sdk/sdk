<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\EventListener;

use SprykerSdk\Sdk\Infrastructure\Injector\HelperSetInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\InputInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\OutputInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\RequestDataInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ApiInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessorInjectorInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApplicationReceiverSetupListener
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Injector\InjectorInterface>
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
     * @param iterable $inputOutputConnectors
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ApiInteractionProcessor $apiInteractionProcessor
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor $cliInteractionProcessor
     */
    public function __construct(
        iterable $inputOutputConnectors,
        ApiInteractionProcessor $apiInteractionProcessor,
        CliInteractionProcessor $cliInteractionProcessor
    ) {
        $this->inputOutputConnectors = $inputOutputConnectors;
        $this->apiInteractionProcessor = $apiInteractionProcessor;
        $this->cliInteractionProcessor = $cliInteractionProcessor;
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
            if ($inputOutputConnector instanceof InputInjectorInterface) {
                $inputOutputConnector->setInput($event->getInput());
            }

            if ($inputOutputConnector instanceof OutputInjectorInterface) {
                $inputOutputConnector->setOutput($event->getOutput());
            }

            if ($inputOutputConnector instanceof RequestDataInjectorInterface) {
                $inputOutputConnector->setRequestData($event->getInput()->getOptions());
            }

            if ($inputOutputConnector instanceof HelperSetInjectorInterface) {
                /** @var \Symfony\Component\Console\Helper\HelperSet $helperSet */
                $helperSet = $event->getCommand()->getHelperSet() ?? $this->getApplicationHelperSet($event);
                $inputOutputConnector->setHelperSet($helperSet);
            }

            if ($inputOutputConnector instanceof InteractionProcessorInjectorInterface) {
                $inputOutputConnector->setInteractionProcessor($this->cliInteractionProcessor);
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        foreach ($this->inputOutputConnectors as $inputOutputConnector) {
            if ($inputOutputConnector instanceof OutputInjectorInterface) {
                $inputOutputConnector->setOutput(new BufferedOutput());
            }

            if ($inputOutputConnector instanceof RequestDataInjectorInterface) {
                $inputOutputConnector->setRequestData($event->getRequest()->request->all());
            }

            if ($inputOutputConnector instanceof HelperSetInjectorInterface) {
                $inputOutputConnector->setHelperSet(new HelperSet([
                    new FormatterHelper(),
                    new DebugFormatterHelper(),
                    new ProcessHelper(),
                    new QuestionHelper(),
                ]));
            }

            if ($inputOutputConnector instanceof InteractionProcessorInjectorInterface) {
                $inputOutputConnector->setInteractionProcessor($this->apiInteractionProcessor);
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
