<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;

interface TaskMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function updateInfrastructureEntity(TaskInterface $task, Task $taskToUpdate): Task;
}
