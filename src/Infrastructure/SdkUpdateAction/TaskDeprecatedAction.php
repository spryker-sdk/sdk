<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface;
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
     * @param array<string> $taskIds
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return void
     */
    public function apply(array $taskIds, array $tasksFromDirectories, array $tasksFromDatabase): void
    {
        foreach ($taskIds as $taskId) {
            $tasksFromDirectory = $tasksFromDirectories[$taskId];

            if ($tasksFromDirectory->getSuccessor()) {
                $successor = $this->taskRepository->find($tasksFromDirectory->getSuccessor());

                if (!$successor) {
                    $this->taskManager->initialize([$tasksFromDirectories[$tasksFromDirectory->getSuccessor()]]);
                }
            }

            $this->taskManager->remove($tasksFromDatabase[$taskId]);
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
            $tasksFromDirectory = $tasksFromDirectories[$taskFromDatabase->getId()] ?? null;

            if ($tasksFromDirectory !== null && $tasksFromDirectory->isDeprecated()) {
                $taskIds[] = $tasksFromDirectory->getId();
            }
        }

        return $taskIds;
    }
}
