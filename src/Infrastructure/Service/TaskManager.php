<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRemoveRepositoryInterface;
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

    protected TaskRemoveRepositoryInterface $taskRemoveRepository;

    protected TaskSaveRepositoryInterface $taskSaveRepository;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRemoveRepositoryInterface $taskRemoveRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskSaveRepositoryInterface $taskSaveRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRemoveRepositoryInterface $taskRemoveRepository,
        TaskSaveRepositoryInterface $taskSaveRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRemoveRepository = $taskRemoveRepository;
        $this->taskSaveRepository = $taskSaveRepository;
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
            $entities[] = $this->taskSaveRepository->create($task);

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
        $this->taskRemoveRepository->remove($task);

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
        $this->taskSaveRepository->update($folderTask, $databaseTask);

        $this->eventDispatcher->dispatch(new UpdatedEvent($folderTask), UpdatedEvent::NAME);
    }
}
