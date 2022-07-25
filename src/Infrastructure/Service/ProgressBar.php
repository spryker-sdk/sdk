<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ProgressBarInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Helper\ProgressBar as SymfonyProgressBar;

class ProgressBar implements ProgressBarInterface
{
    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected SymfonyProgressBar $symfonyProgressBar;

    /**
     * @param int|null $max
     *
     * @return void
     */
    public function start(?int $max = null): void
    {
        $this->symfonyProgressBar->start($max);
    }

    /**
     * @param int $max
     *
     * @return void
     */
    public function setMaxSteps(int $max): void
    {
        $this->symfonyProgressBar->setMaxSteps($max);
    }

    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     *
     * @return void
     */
    public function advance(int $step = 1): void
    {
        $this->symfonyProgressBar->advance($step);
    }

    /**
     * @return void
     */
    public function finish(): void
    {
        $this->symfonyProgressBar->finish();
    }

    /**
     * @param string $message The text to associate with the placeholder
     * @param string $name The name of the placeholder
     *
     * @return void
     */
    public function setMessage(string $message, string $name = 'message'): void
    {
        $this->symfonyProgressBar->setMessage($message, $name);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function initProgressBar(ConsoleCommandEvent $event)
    {
        $this->symfonyProgressBar = new SymfonyProgressBar($event->getOutput());
        $this->symfonyProgressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% \n\r <comment>%message%</comment> \n\r");
        $this->symfonyProgressBar->setMessage('');
    }
}
