<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Repository;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskSaveRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function create(TaskInterface $task);

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    public function update(TaskInterface $task, TaskInterface $taskToUpdate): TaskInterface;
}
