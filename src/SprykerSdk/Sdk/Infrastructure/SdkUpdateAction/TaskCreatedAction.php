<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;

class TaskCreatedAction implements SdkUpdateActionInterface
{
    protected TaskManagerInterface $taskManager;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     */
    public function __construct(TaskManagerInterface $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * @param array<string> $taskIds
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void
    {
        $tasks = [];

        foreach ($taskIds as $taskId) {
            $tasks[] = $folderTasks[$taskId];
        }

        $this->taskManager->initialize($tasks);
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return array<string>
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
