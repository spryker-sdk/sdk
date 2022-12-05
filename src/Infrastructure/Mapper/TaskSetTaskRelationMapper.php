<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use Doctrine\Persistence\ObjectRepository;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation as DomainTaskSetRelation;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation as InfrastructureTaskSetRelation;

class TaskSetTaskRelationMapper implements TaskSetTaskRelationMapperInterface
{
    /**
     * @var \Doctrine\Persistence\ObjectRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Task>
     */
    protected ObjectRepository $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface
     */
    protected TaskMapperInterface $taskMapper;

    /**
     * @param \Doctrine\Persistence\ObjectRepository $taskRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface $taskMapper
     */
    public function __construct(ObjectRepository $taskRepository, TaskMapperInterface $taskMapper)
    {
        $this->taskRepository = $taskRepository;
        $this->taskMapper = $taskMapper;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $taskSetRelation
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation
     */
    public function mapToInfrastructureTaskSetRelation(TaskSetTaskRelationInterface $taskSetRelation): InfrastructureTaskSetRelation
    {
        $taskSet = $this->taskRepository->find($taskSetRelation->getTaskSet()->getId());

        if ($taskSet === null) {
            $taskSet = $this->taskMapper->mapToInfrastructureEntity($taskSetRelation->getTaskSet());
        }

        $subTask = $this->taskRepository->find($taskSetRelation->getSubTask()->getId());

        if ($subTask === null) {
            $subTask = $this->taskMapper->mapToInfrastructureEntity($taskSetRelation->getSubTask());
        }

        return new InfrastructureTaskSetRelation($taskSet, $subTask);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $taskSetRelation
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation
     */
    public function mapToDomainTaskSetRelation(TaskSetTaskRelationInterface $taskSetRelation): DomainTaskSetRelation
    {
        return new DomainTaskSetRelation(
            $taskSetRelation->getTaskSet(),
            $taskSetRelation->getSubTask(),
        );
    }
}
