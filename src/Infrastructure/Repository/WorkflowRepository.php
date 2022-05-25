<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Workflow>
 */
class WorkflowRepository extends ServiceEntityRepository implements WorkflowRepositoryInterface
{
    /**
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workflow::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    public function save(WorkflowInterface $workflow): WorkflowInterface
    {
        $this->getEntityManager()->persist($workflow);
        $this->getEntityManager()->flush();

        return $workflow;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $project
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null
     */
    public function findOne(string $project): ?WorkflowInterface
    {
        $criteria = [
            'project' => $project,
        ];

        return $this->findOneBy($criteria);
    }
}
