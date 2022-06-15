<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SprykerSdk\Sdk\Core\Domain\Entity\Workflow as EntityWorkflow;
use SprykerSdk\SdkContracts\Entity\WorkflowEventInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

class Workflow extends EntityWorkflow
{
    /**
     * @var int
     */
    protected int $id;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\SdkContracts\Entity\WorkflowInterface>
     */
    protected Collection $children;

    /**
     * @psalm-var \Doctrine\Common\Collections\Collection<int, \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface>
     */
    protected Collection $events;

    /**
     * @param string $project
     * @param array $status
     * @param string $workflow
     * @param string|null $code
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null $parent
     */
    public function __construct(
        string $project,
        array $status,
        string $workflow,
        ?string $code = null,
        ?WorkflowInterface $parent = null
    ) {
        $this->children = new ArrayCollection();
        $this->events = new ArrayCollection();

        parent::__construct($project, $status, $workflow, $code, $parent);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $project
     *
     * @return $this
     */
    public function setProject(string $project)
    {
        $this->project = $project;

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
     * @param string $workflow
     *
     * @return $this
     */
    public function setWorkflow(string $workflow)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null $parent
     *
     * @return $this
     */
    public function setParent(?WorkflowInterface $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface $event
     *
     * @return $this
     */
    public function addEvent(WorkflowEventInterface $event)
    {
        $this->events[] = $event;

        return $this;
    }
}
