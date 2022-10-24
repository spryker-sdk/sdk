<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Creator\TaskCreatorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
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
     * @var \SprykerSdk\Sdk\Core\Application\Creator\TaskCreatorInterface
     */
    protected TaskCreatorInterface $taskCreator;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Application\Creator\TaskCreatorInterface $taskCreator
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRepositoryInterface $taskRepository,
        TaskCreatorInterface $taskCreator
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRepository = $taskRepository;
        $this->taskCreator = $taskCreator;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function initialize(InitializeCriteriaDto $criteriaDto): InitializeResultDto
    {
        return $this->taskCreator->createTasks($criteriaDto);
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
