<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface, TaskSaveRepositoryInterface
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
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface> $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function save(TaskInterface $task): Task
    {
        $entity = $this->taskMapper->mapToInfrastructureEntity($task);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface
    {
        /** @var TaskInterface|null $task */
        $task = $this->find($taskId);

        return $task;
    }
}
