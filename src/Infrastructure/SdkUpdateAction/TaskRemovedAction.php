<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;

class TaskRemovedAction implements SdkUpdateActionInterface
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
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return void
     */
    public function apply(array $taskIds, array $tasksFromDirectories, array $tasksFromDatabase): void
    {
        foreach ($taskIds as $taskId) {
            $databaseTask = $tasksFromDatabase[$taskId];

            $this->taskManager->remove($databaseTask);
        }
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return array<string>
     */
    public function filter(array $tasksFromDirectories, array $tasksFromDatabase): array
    {
        $taskIds = [];

        foreach ($tasksFromDatabase as $taskFromDatabase) {
            if (!isset($tasksFromDirectories[$taskFromDatabase->getId()])) {
                $taskIds[] = $taskFromDatabase->getId();
            }
        }

        return $taskIds;
    }
}
