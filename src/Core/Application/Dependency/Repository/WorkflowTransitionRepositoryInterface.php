<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

interface WorkflowTransitionRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface>
     */
    public function getAll(WorkflowInterface $workflow): array;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface|null
     */
    public function findLast(WorkflowInterface $workflow): ?WorkflowTransitionInterface;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface $event
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface
     */
    public function save(WorkflowTransitionInterface $event): WorkflowTransitionInterface;
}
