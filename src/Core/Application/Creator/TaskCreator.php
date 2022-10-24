<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Creator;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskCreator implements TaskCreatorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface
     */
    protected TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TaskFromTaskSetBuilderInterface $taskFromTaskSetBuilder
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskFromTaskSetBuilder = $taskFromTaskSetBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function createTasks(InitializeCriteriaDto $criteriaDto): InitializeResultDto
    {
        $resultDto = new InitializeResultDto();

        foreach ($criteriaDto->getTaskCollection() as $task) {
            $task = $this->createTask($criteriaDto, $task);
            if ($task !== null) {
                $resultDto->addTask($task);
            }
        }

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    protected function createTask(InitializeCriteriaDto $criteriaDto, TaskInterface $task): ?TaskInterface
    {
        $existingTask = $this->taskRepository->findById($task->getId());

        if ($existingTask) {
            return null;
        }

        if ($task instanceof TaskSetInterface) {
            $task = $this->taskFromTaskSetBuilder->buildTaskFromTaskSet($task, $criteriaDto->getTaskCollection());
        }

        return $this->taskRepository->create($task);
    }
}
