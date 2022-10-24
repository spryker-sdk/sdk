<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;
use SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\Processor\AfterTaskInitProcessor;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskManager implements TaskManagerInterface
{
    /**
     * @todo :: must be replaced by plugin processor
     *
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
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\Processor\AfterTaskInitProcessor
     */
    protected AfterTaskInitProcessor $afterTaskInitProcessor;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\Processor\AfterTaskInitProcessor $afterTaskInitProcessor
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRepositoryInterface $taskRepository,
        TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder,
        AfterTaskInitProcessor $afterTaskInitProcessor
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRepository = $taskRepository;
        $this->taskFromTaskSetBuilder = $taskFromTaskSetBuilder;
        $this->afterTaskInitProcessor = $afterTaskInitProcessor;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function initialize(InitializeCriteriaDto $criteriaDto): InitializeResultDto
    {
        $resultDto = new InitializeResultDto();

        foreach ($criteriaDto->getTaskCollection() as $task) {
            $existingTask = $this->taskRepository->findById($task->getId());

            if ($existingTask) {
                continue;
            }

            if ($task instanceof TaskSetInterface) {
                $task = $this->taskFromTaskSetBuilder->buildTaskFromTaskSet($task, $criteriaDto->getTaskCollection());
            }

            $resultDto->addTask($this->taskRepository->create($task));
            $afterTaskInitDto = new AfterTaskInitDto($task, $criteriaDto->getSourceType());
            $this->afterTaskInitProcessor->processAfterTaskInitialization($afterTaskInitDto);
        }

        return $resultDto;
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
