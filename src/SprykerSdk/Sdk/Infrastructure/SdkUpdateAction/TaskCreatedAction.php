<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskInitializerInterface;

class TaskCreatedAction implements SdkUpdateActionInterface
{
    protected TaskInitializerInterface $taskInitializer;

    public function __construct(TaskInitializerInterface $taskInitializer)
    {
        $this->taskInitializer = $taskInitializer;
    }

    /**
     * @param string[] $taskIds
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void
    {
        $tasks = [];

        foreach ($taskIds as $taskId) {
            $tasks[] = $folderTasks[$taskId];
        }

        $this->taskInitializer->initialize($tasks);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return string[]
     */
    public function filter(array $folderTasks, array $databaseTasks): array
    {
        $taskIds = [];

        foreach ($folderTasks as $folderTask) {
            if (!isset($databaseTasks[$folderTask->getId()])) {
                $taskIds[] = $folderTask->getId();
            }
        }

        return $taskIds;
    }
}
