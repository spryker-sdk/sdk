<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\Sdk\Extension\Dependency\Event\WorkflowEventInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Component\Workflow\Event\Event;

class WorkflowEvent implements WorkflowEventInterface
{
    /**
     * @var \Symfony\Component\Workflow\Event\Event
     */
    protected Event $event;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context;

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface|null $context
     */
    public function __construct(Event $event, ?ContextInterface $context = null)
    {
        $this->event = $event;
        $this->context = $context;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    /**
     * @return \Symfony\Component\Workflow\Event\Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }
}
