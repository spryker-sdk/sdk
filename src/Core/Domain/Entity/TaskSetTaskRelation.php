<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskSetTaskRelation implements TaskSetTaskRelationInterface
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected TaskInterface $taskSet;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected TaskInterface $subTask;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $taskSet
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $subTask
     */
    public function __construct(TaskInterface $taskSet, TaskInterface $subTask)
    {
        $this->taskSet = $taskSet;
        $this->subTask = $subTask;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTaskSet(): TaskInterface
    {
        return $this->taskSet;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getSubTask(): TaskInterface
    {
        return $this->subTask;
    }
}
