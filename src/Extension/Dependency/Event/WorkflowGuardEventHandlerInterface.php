<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Dependency\Event;

use Symfony\Component\Workflow\Event\GuardEvent;

interface WorkflowGuardEventHandlerInterface
{
    /**
     * @param \Symfony\Component\Workflow\Event\GuardEvent $event
     *
     * @return void
     */
    public function check(GuardEvent $event): void;
}
