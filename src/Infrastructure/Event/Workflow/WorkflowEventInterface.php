<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Contracts\EventDispatcher\Event;

interface WorkflowEventInterface
{
    /**
     * @return \Symfony\Contracts\EventDispatcher\Event
     */
    public function getEvent(): Event;

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function getContext(): ?ContextInterface;
}
