<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function updateInfrastructureEntity(TaskInterface $task, Task $taskToUpdate): Task;
}
