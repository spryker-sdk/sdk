<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Logger;

use SprykerSdk\Sdk\Core\Domain\Event\EventInterface;

interface EventLoggerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Event\EventInterface $event
     *
     * @return void
     */
    public function logEvent(EventInterface $event): void;
}
