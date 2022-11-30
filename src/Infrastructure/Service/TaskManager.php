<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation\TaskSetTaskRelationFacadeInterface;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Infrastructure\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskManager implements TaskManagerInterface
{
    /**
     * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface
     */
    protected TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation\TaskSetTaskRelationFacadeInterface
     */
    protected TaskSetTaskRelationFacadeInterface $taskSetTaskRelationFacade;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation\TaskSetTaskRelationFacadeInterface $taskSetTaskRelationFacade
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRepositoryInterface $taskRepository,
        TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder,
        TaskSetTaskRelationFacadeInterface $taskSetTaskRelationFacade
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRepository = $taskRepository;
        $this->taskFromTaskSetBuilder = $taskFromTaskSetBuilder;
        $this->taskSetTaskRelationFacade = $taskSetTaskRelationFacade;
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
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

            if ($task instanceof TaskSetInterface) {
                $this->taskSetTaskRelationFacade->collectTaskSet($task, $tasks);
                $task = $this->taskFromTaskSetBuilder->buildTaskFromTaskSet($task, $tasks);
            }

            $entities[] = $this->taskRepository->create($task);
            $this->taskSetTaskRelationFacade->createRelation($task);
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
        $this->taskSetTaskRelationFacade->removeRelation($task);

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
        $this->taskSetTaskRelationFacade->updateRelation($folderTask);

        $this->eventDispatcher->dispatch(new UpdatedEvent($folderTask), UpdatedEvent::NAME);
    }
}
