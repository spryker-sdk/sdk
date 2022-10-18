<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

interface WorkflowEventHandlerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowEventInterface $event
     *
     * @return void
     */
    public function handle(WorkflowEventInterface $event): void;
}
