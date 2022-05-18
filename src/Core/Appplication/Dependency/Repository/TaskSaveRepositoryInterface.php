<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskSaveRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function create(TaskInterface $task): TaskInterface;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $taskToUpdate
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function update(TaskInterface $task, TaskInterface $taskToUpdate): TaskInterface;
}
