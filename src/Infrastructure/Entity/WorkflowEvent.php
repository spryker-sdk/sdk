<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use DateTimeInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\WorkflowEvent as EntityWorkflowEvent;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

class WorkflowEvent extends EntityWorkflowEvent
{
    /**
     * @var int
     */
    protected int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return $this
     */
    public function setWorkflow(WorkflowInterface $workflow)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * @param array $status
     *
     * @return $this
     */
    public function setStatus(array $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string|null $transition
     *
     * @return $this
     */
    public function setTransition(?string $transition)
    {
        $this->transition = $transition;

        return $this;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent(string $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @param \DateTimeInterface $time
     *
     * @return $this
     */
    public function setTime(DateTimeInterface $time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
