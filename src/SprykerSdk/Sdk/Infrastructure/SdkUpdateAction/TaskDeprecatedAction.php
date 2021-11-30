<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;

class TaskDeprecatedAction implements SdkUpdateActionInterface
{
    protected TaskRepository $taskRepository;

    protected TaskManagerInterface $taskManager;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     */
    public function __construct(
        TaskRepository $taskRepository,
        TaskManagerInterface $taskManager
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskManager = $taskManager;
    }

    /**
     * @param string[] $taskIds
     * @param array<string,\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<string,\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void
    {
        foreach ($taskIds as $taskId) {
            $folderTask = $folderTasks[$taskId];

            if ($folderTask->getSuccessor()) {
                $successor = $this->taskRepository->find($folderTask->getSuccessor());

                if (!$successor) {
                    $this->taskManager->initialize([$folderTasks[$folderTask->getSuccessor()]]);
                }
            }

            $this->taskManager->remove($databaseTasks[$taskId]);
        }
    }

    /**
     * @param array<string,\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<string,\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return string[]
     */
    public function filter(array $folderTasks, array $databaseTasks): array
    {
        $taskIds = [];

        foreach ($databaseTasks as $databaseTask) {
            $folderTask = $folderTasks[$databaseTask->getId()] ?? null;

            if ($folderTask !== null && $folderTask->isDeprecated()) {
                $taskIds[] = $folderTask->getId();
            }
        }

        return $taskIds;
    }
}
