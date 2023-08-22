<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Doctrine\DBAL\Exception\TableNotFoundException;
use SprykerSdk\Sdk\Core\Application\Dependency\ErrorCommandListenerInterface;
use SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException;
use SprykerSdk\Sdk\Presentation\Console\Command\InitSdkCommand;
use SprykerSdk\Sdk\Presentation\Console\Command\UpdateSdkCommand;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

class ErrorCommandListener implements ErrorCommandListenerInterface
{
    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @param bool $isDebug
     */
    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    public function handle(ConsoleErrorEvent $event): void
    {
        if ($this->isDebug && $event->getOutput()->isDebug()) {
            return;
        }

        $event->setExitCode(0);

        if ($event->getError() instanceof TableNotFoundException || $event->getError() instanceof SettingsNotInitializedException) {
            $event->getOutput()->writeln(sprintf('<error>You need to init or update the SDK. you need to run \'%s\' or \'%s\' command.</error>', InitSdkCommand::NAME, UpdateSdkCommand::NAME));

            return;
        }

        $event->getOutput()->writeln('<error>' . $event->getError()->getMessage() . '</error>');
    }
}
