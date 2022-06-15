<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\WorkflowEventInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

interface WorkflowEventRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     * @param string|null $transition
     * @param array $events
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\WorkflowEventInterface>
     */
    public function searchByWorkflow(WorkflowInterface $workflow, ?string $transition = null, array $events = []): array;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface $event
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface
     */
    public function save(WorkflowEventInterface $event): WorkflowEventInterface;
}
