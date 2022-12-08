<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation as InfrastructureTaskSetRelation;

class TaskSetTaskRelationMapper implements TaskSetTaskRelationMapperInterface
{
    /**
     * @var \Doctrine\Persistence\ObjectRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Task>
     */
    protected ObjectRepository $taskRepository;

    /**
     * @param \Doctrine\Persistence\ObjectRepository $taskRepository
     */
    public function __construct(ObjectRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $taskSetRelation
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation
     */
    public function mapToInfrastructureTaskSetRelation(TaskSetTaskRelationInterface $taskSetRelation): InfrastructureTaskSetRelation
    {
        $taskSet = $this->taskRepository->find($taskSetRelation->getTaskSet()->getId());

        if ($taskSet === null) {
            throw new InvalidArgumentException(sprintf('Task set `%s` is not found', $taskSetRelation->getTaskSet()->getId()));
        }

        $subTask = $this->taskRepository->find($taskSetRelation->getSubTask()->getId());

        if ($subTask === null) {
            throw new InvalidArgumentException(sprintf('Sub-task set `%s` is not found', $taskSetRelation->getSubTask()->getId()));
        }

        return new InfrastructureTaskSetRelation($taskSet, $subTask);
    }
}
