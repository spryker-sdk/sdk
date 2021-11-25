<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Logger;

use SprykerSdk\Sdk\Contracts\Events\EventInterface;

interface EventLoggerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Events\EventInterface $event
     * @return void
     */
    public function logEvent(EventInterface $event): void;
}
