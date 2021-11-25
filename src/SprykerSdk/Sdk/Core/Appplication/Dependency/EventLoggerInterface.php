<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Domain\Events\Event;

interface EventLoggerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Events\Event $event
     *
     * @return void
     */
    public function logEvent(Event $event): void;
}
