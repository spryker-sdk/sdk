<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRemoveRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskManager implements TaskManagerInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    protected TaskRepositoryInterface | TaskRemoveRepositoryInterface | TaskSaveRepositoryInterface $taskRepository;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRemoveRepositoryInterface|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskSaveRepositoryInterface $taskRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRepositoryInterface | TaskRemoveRepositoryInterface | TaskSaveRepositoryInterface $taskRepository,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function initialize(array $tasks): array
    {
        $entities = [];

        foreach ($tasks as $task) {
            $existingTask = $this->taskRepository->findById($task->getId());

            if ($existingTask) {
                continue;
            }

            $entities[] = $this->taskRepository->create($task);
            $this->eventDispatcher->dispatch(new InitializedEvent($task), InitializedEvent::NAME);
        }

        return $entities;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void
    {
        $this->taskRepository->remove($task);

        $this->eventDispatcher->dispatch(new RemovedEvent($task), RemovedEvent::NAME);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $folderTask
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $databaseTask
     *
     * @return void
     */
    public function update(TaskInterface $folderTask, TaskInterface $databaseTask): void
    {
        $this->taskRepository->update($folderTask, $databaseTask);

        $this->eventDispatcher->dispatch(new UpdatedEvent($folderTask), UpdatedEvent::NAME);
    }
}
