<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskManagerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $tasks
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[]
     */
    public function initialize(array $tasks): array;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $folderTask
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $databaseTask
     *
     * @return void
     */
    public function update(TaskInterface $folderTask, TaskInterface $databaseTask): void;
}
