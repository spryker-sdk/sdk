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
     * @param string $project
     * @param array $status
     * @param string $workflow
     */
    public function __construct(string $project, array $status, string $workflow)
    {
        $this->project = $project;
        $this->status = $status;
        $this->workflow = $workflow;
    }

    /**
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @return array
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getWorkflow(): string
    {
        return $this->workflow;
    }
}
