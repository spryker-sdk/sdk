<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskSetTaskRelationMapperInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation>
 */
class TaskSetTaskRelationRepository extends ServiceEntityRepository implements TaskSetTaskRelationRepositoryInterface
{
    protected TaskSetTaskRelationMapperInterface $taskSetTaskRelationMapper;

    /**
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TaskSetTaskRelationMapperInterface $taskSetTaskRelationMapper
     */
    public function __construct(
        ManagerRegistry $registry,
        TaskSetTaskRelationMapperInterface $taskSetTaskRelationMapper
    ) {
        $this->taskSetTaskRelationMapper = $taskSetTaskRelationMapper;

        parent::__construct($registry, TaskSetTaskRelation::class);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $relation
     *
     * @return void
     */
    public function create(TaskSetTaskRelationInterface $relation): void
    {
        $this->getEntityManager()->persist(
            $this->taskSetTaskRelationMapper->mapToInfrastructureTaskSetRelation($relation),
        );

        $this->getEntityManager()->flush();
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface> $relations
     *
     * @return void
     */
    public function createMany(array $relations): void
    {
        foreach ($relations as $relation) {
            $this->getEntityManager()->persist(
                $this->taskSetTaskRelationMapper->mapToInfrastructureTaskSetRelation($relation),
            );
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param string $taskSetId
     *
     * @return void
     */
    public function removeByTaskSetId(string $taskSetId): void
    {
        $qb = $this->createQueryBuilder('tsr');

        $relations = $qb
            ->innerJoin('tsr.taskSet', 'ts')
            ->where($qb->expr()->eq('ts.id', ':taskSetId'))
            ->setParameters([':taskSetId' => $taskSetId])
            ->getQuery()
            ->getResult();

        foreach ($relations as $relation) {
            $this->getEntityManager()->remove($relation);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param string $taskSetId
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function getByTaskSetId(string $taskSetId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb
            ->select('tsr', 'ts')
            ->from(TaskSetTaskRelation::class, 'tsr')
            ->innerJoin('tsr.taskSet', 'ts')
            ->where($qb->expr()->eq('ts.id', ':taskSetId'))
            ->setParameters([':taskSetId' => $taskSetId])
            ->getQuery()
            ->getResult();
    }
}
