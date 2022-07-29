<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Telemetry;

use SprykerSdk\Sdk\Presentation\Console\Commands\RunTaskWrapperCommand;
use Symfony\Component\Console\Event\ConsoleEvent;

class TelemetryConsoleEventValidator implements TelemetryConsoleEventValidatorInterface
{
    /**
     * @var iterable<\Symfony\Component\Console\Command\Command>
     */
    protected iterable $allowedCommands;

    /**
     * @param iterable<\Symfony\Component\Console\Command\Command> $allowedCommands
     */
    public function __construct(iterable $allowedCommands)
    {
        $this->allowedCommands = $allowedCommands;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleEvent $event
     *
     * @return bool
     */
    public function isValid(ConsoleEvent $event): bool
    {
        $targetCommand = $event->getCommand();

        if ($targetCommand === null) {
            return false;
        }

        if ($targetCommand->getName() === null) {
            return false;
        }

        if ($targetCommand instanceof RunTaskWrapperCommand) {
            return true;
        }

        foreach ($this->allowedCommands as $allowedCommand) {
            if ($targetCommand instanceof $allowedCommand) {
                return true;
            }
        }

        return false;
    }
}
