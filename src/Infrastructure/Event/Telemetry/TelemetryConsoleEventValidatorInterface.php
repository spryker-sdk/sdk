<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Telemetry;

use Symfony\Component\Console\Event\ConsoleEvent;

interface TelemetryConsoleEventValidatorInterface
{
    /**
     * @param \Symfony\Component\Console\Event\ConsoleEvent $event
     *
     * @return bool
     */
    public function isValid(ConsoleEvent $event): bool;
}
