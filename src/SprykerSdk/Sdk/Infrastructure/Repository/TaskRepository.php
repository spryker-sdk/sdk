<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRemoveRepositoryInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface, TaskSaveRepositoryInterface, TaskRemoveRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface $taskMapper
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(
        protected TaskMapperInterface $taskMapper,
        protected ManagerRegistry $registry
    ) {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function create(TaskInterface $task): Task
    {
        $entity = $this->taskMapper->mapToInfrastructureEntity($task);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    public function update(TaskInterface $task, TaskInterface $taskToUpdate): TaskInterface
    {
        if (!$taskToUpdate instanceof Task) {
            throw new InvalidTypeException(sprintf('$taskToUpdate must be instance of %s', Task::class));
        }

        $this->getEntityManager()->remove($taskToUpdate->getLifecycle());
        $this->getEntityManager()->flush();

        $entity = $this->taskMapper->updateInfrastructureEntity($task, $taskToUpdate);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId, array $tags = []): ?TaskInterface
    {
        /** @var TaskInterface|null $task */
        $task = $this->find($taskId);

        return $task;
    }

    /**
     * @return array<string, \SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function findAll(): array
    {
        $tasks = parent::findAll();

        $tasksMap = [];

        foreach ($tasks as $task) {
            $tasksMap[$task->getId()] = $task;
        }

        return $tasksMap;
    }

    public function remove(TaskInterface $task): void
    {
        if (!$task instanceof Task) {
            return;
        }

        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}
