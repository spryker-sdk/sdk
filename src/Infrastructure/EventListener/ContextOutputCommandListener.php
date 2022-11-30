<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\EventListener;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Output\OutputInterface;

class ContextOutputCommandListener
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     */
    public function __construct(ContextFactoryInterface $contextFactory)
    {
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function handle(ConsoleTerminateEvent $event): void
    {
        if (!$this->contextFactory->hasContext()) {
            return;
        }

        $messages = $this->contextFactory->getContext()->getMessages();

        if (!$messages) {
            return;
        }

        $verbosity = $this->getVerbosity($event->getOutput());

        foreach ($messages as $message) {
            if ($message->getVerbosity() <= $verbosity) {
                $event->getOutput()->writeln($this->formatMessage($message));
            }
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\MessageInterface $message
     *
     * @return string
     */
    protected function formatMessage(MessageInterface $message): string
    {
        $template = [
            MessageInterface::INFO => '<info>Info: %s</info>',
            MessageInterface::ERROR => '<error>Error: %s</error>',
            MessageInterface::SUCCESS => '<fg=black;bg=green>Success: %s</>',
            MessageInterface::DEBUG => '<fg=black;bg=yellow>Debug: %s</>',
        ][$message->getVerbosity()] ?? '%s';

        return sprintf($template, $message->getMessage());
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function getVerbosity(OutputInterface $output): int
    {
        if ($output->isVerbose()) {
            return MessageInterface::SUCCESS;
        }

        if ($output->isVeryVerbose()) {
            return MessageInterface::INFO;
        }

        if ($output->isDebug()) {
            return MessageInterface::DEBUG;
        }

        return MessageInterface::ERROR;
    }
}
