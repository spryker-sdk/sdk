<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Dependency\Events;

interface GuardHandlerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Extension\Dependency\Events\GuardEventInterface $event
     *
     * @return void
     */
    public function check(GuardEventInterface $event): void;
}
