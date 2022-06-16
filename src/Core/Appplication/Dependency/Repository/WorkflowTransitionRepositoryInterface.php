<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;

interface WorkflowTransitionRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface>
     */
    public function getAll(WorkflowInterface $workflow): array;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface|null
     */
    public function getLast(WorkflowInterface $workflow): ?WorkflowTransitionInterface;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $event
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface
     */
    public function save(WorkflowTransitionInterface $event): WorkflowTransitionInterface;
}
