<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Doctrine\DBAL\Exception\TableNotFoundException;
use SprykerSdk\Sdk\Core\Application\Dependency\ErrorCommandListenerInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ProjectWorkflowException;
use SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

class ErrorCommandListener implements ErrorCommandListenerInterface
{
    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    public function handle(ConsoleErrorEvent $event): void
    {
        if (
            !($event->getError() instanceof TableNotFoundException || $event->getError() instanceof SettingsNotInitializedException)
            || $event->getOutput()->isDebug()
        ) {
            return;
        }

        $event->setError(
            new ProjectWorkflowException(
                'You need to init or update the SDK. you need to run \'sdk:init:sdk\' or \'sdk:update:all\' command.',
                0,
                $event->getError(),
            ),
        );
        $event->setExitCode(0);
    }
}
