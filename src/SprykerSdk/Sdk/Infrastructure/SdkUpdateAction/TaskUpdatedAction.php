<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use Composer\Semver\Comparator;
use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;

class TaskUpdatedAction implements SdkUpdateActionInterface
{
    protected TaskManagerInterface $taskManager;

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
        foreach ($taskIds as $taskId) {
            $folderTask = $folderTasks[$taskId];
            $databaseTask = $databaseTasks[$taskId];

            $this->taskManager->update($folderTask, $databaseTask);
        }
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
            $databaseTask = $databaseTasks[$folderTask->getId()] ?? null;

            if ($databaseTask !== null && Comparator::greaterThan($folderTask->getVersion(), $databaseTask->getVersion())) {
                $taskIds[] = $folderTask->getId();
            }
        }

        return $taskIds;
    }
}
