<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRemoveRepositoryInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Core\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Lifecycle\Event\UpdatedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskManager implements TaskManagerInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    protected TaskRemoveRepositoryInterface $taskRemoveRepository;

    protected TaskSaveRepositoryInterface $taskSaveRepository;

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
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[]
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void
    {
        $this->taskRemoveRepository->remove($task);

        $this->eventDispatcher->dispatch(new RemovedEvent($task), RemovedEvent::NAME);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $folderTask
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $databaseTask
     *
     * @return void
     */
    public function update(TaskInterface $folderTask, TaskInterface $databaseTask): void
    {
        $this->taskSaveRepository->update($folderTask, $databaseTask);

        $this->eventDispatcher->dispatch(new UpdatedEvent($folderTask), UpdatedEvent::NAME);
    }
}
