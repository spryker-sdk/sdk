<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

interface WorkflowRepositoryInterface
{
    /**
     * @param string $project
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\WorkflowInterface>
     */
    public function findWorkflows(string $project): array;

    /**
     * @param string $project
     * @param string|null $workflowName
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    public function getWorkflow(string $project, ?string $workflowName = null): ?WorkflowInterface;

    /**
     * @param string $project
     *
     * @return bool
     */
    public function hasWorkflow(string $project): bool;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    public function save(WorkflowInterface $workflow): WorkflowInterface;

    /**
     * @return void
     */
    public function flush(): void;
}
