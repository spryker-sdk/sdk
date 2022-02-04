<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Workflow as EntityWorkflow;

class Workflow extends EntityWorkflow
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
     */
    public function setProject(string $project): void
    {
        $this->project = $project;
    }

    /**
     * @param array $status
     */
    public function setStatus(array $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $workflow
     */
    public function setWorkflow(string $workflow): void
    {
        $this->workflow = $workflow;
    }
}
