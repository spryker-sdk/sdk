<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Entity\WorkflowTransition;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\WorkflowTransition>
 */
class WorkflowTransitionRepository extends ServiceEntityRepository implements WorkflowTransitionRepositoryInterface
{
    /**
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowTransition::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface>
     */
    public function getAll(WorkflowInterface $workflow): array
    {
        $filter = [
            'workflow' => $workflow instanceof Workflow ? $workflow->getId() : 0,
        ];

        return $this->findBy($filter, ['time' => 'desc', 'id' => 'desc']);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface|null
     */
    public function getLast(WorkflowInterface $workflow): ?WorkflowTransitionInterface
    {
        $filter = [
            'workflow' => $workflow instanceof Workflow ? $workflow->getId() : 0,
        ];

        return $this->findOneBy($filter, ['time' => 'desc', 'id' => 'desc']);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface $event
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface
     */
    public function save(WorkflowTransitionInterface $event): WorkflowTransitionInterface
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();

        return $event;
    }
}
