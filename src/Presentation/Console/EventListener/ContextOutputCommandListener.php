<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\EventListener;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class ContextOutputCommandListener
{
    /**
     * @var array
     */
    protected const TEMPLATES = [
            MessageInterface::INFO => '<info>Info: %s</info>',
            MessageInterface::ERROR => '<error>Error: %s</error>',
            MessageInterface::SUCCESS => '<fg=black;bg=green>Success: %s</>',
            MessageInterface::DEBUG => '<fg=black;bg=yellow>Debug: %s</>',
        ];

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

        $isDebug = $event->getOutput()->isDebug();

        foreach ($messages as $message) {
            if ($isDebug || $message->getVerbosity() !== MessageInterface::DEBUG) {
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
        $template = static::TEMPLATES[$message->getVerbosity()] ?? '%s';

        return sprintf($template, $message->getMessage());
    }
}
