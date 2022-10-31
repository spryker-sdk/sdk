<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

class Workflow implements WorkflowInterface
{
    /**
     * @var string
     */
    protected string $project;

    /**
     * @var array
     */
    protected array $status;

    /**
     * @var string
     */
    protected string $workflow;

    /**
     * @var string
     */
    protected string $code;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    protected ?WorkflowInterface $parent;

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
        $this->project = $project;
        $this->status = $status;
        $this->workflow = $workflow;
        $this->code = $code ?? $workflow;
        $this->parent = $parent;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getWorkflow(): string
    {
        return $this->workflow;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    public function getParent(): ?WorkflowInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $statuses
     *
     * @return void
     */
    public function setStatus(array $statuses): void
    {
        $this->status = $statuses;
    }
}
