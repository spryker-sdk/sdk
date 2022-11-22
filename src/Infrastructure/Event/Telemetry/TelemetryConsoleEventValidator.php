<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Telemetry;

use SprykerSdk\Sdk\Presentation\Console\Command\RunTaskWrapperCommand;
use Symfony\Component\Console\Event\ConsoleEvent;

class TelemetryConsoleEventValidator implements TelemetryConsoleEventValidatorInterface
{
    /**
     * @var iterable<class-string>
     */
    protected iterable $allowedCommandClasses;

    /**
     * @param iterable<class-string> $allowedCommandClasses
     */
    public function __construct(iterable $allowedCommandClasses)
    {
        $this->allowedCommandClasses = $allowedCommandClasses;
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

        foreach ($this->allowedCommandClasses as $allowedCommandClass) {
            if ($targetCommand instanceof $allowedCommandClass) {
                return true;
            }
        }

        return false;
    }
}
