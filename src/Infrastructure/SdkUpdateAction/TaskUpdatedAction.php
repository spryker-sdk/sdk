<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use Composer\Semver\Comparator;
use SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;

class TaskUpdatedAction implements SdkUpdateActionInterface
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
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return void
     */
    public function apply(array $taskIds, array $tasksFromDirectories, array $tasksFromDatabase): void
    {
        foreach ($taskIds as $taskId) {
            $folderTask = $tasksFromDirectories[$taskId];
            $databaseTask = $tasksFromDatabase[$taskId];

            $this->taskManager->update($folderTask, $databaseTask);
        }
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return array<string>
     */
    public function filter(array $tasksFromDirectories, array $tasksFromDatabase): array
    {
        $taskIds = [];

        foreach ($tasksFromDirectories as $tasksFromDirectory) {
            $taskFromDatabase = $tasksFromDatabase[$tasksFromDirectory->getId()] ?? null;

            if ($taskFromDatabase !== null && Comparator::greaterThan($tasksFromDirectory->getVersion(), $taskFromDatabase->getVersion())) {
                $taskIds[] = $tasksFromDirectory->getId();
            }
        }

        return $taskIds;
    }
}
