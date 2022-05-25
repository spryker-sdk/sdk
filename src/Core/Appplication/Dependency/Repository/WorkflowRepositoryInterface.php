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
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    public function findOne(string $project): ?WorkflowInterface;

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
