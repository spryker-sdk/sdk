<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Telemetry\TelemetryEventsQueryCriteria;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent;
use SprykerSdk\Sdk\Infrastructure\Mapper\TelemetryEventMapperInterface;

class TelemetryEventRepository extends EntityRepository implements TelemetryEventRepositoryInterface
{
    protected TelemetryEventMapperInterface $telemetryEventMapper;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TelemetryEventMapperInterface $telemetryEventMapper
     */
    public function __construct(EntityManagerInterface $entityManager, TelemetryEventMapperInterface $telemetryEventMapper)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata<\SprykerSdk\SdkContracts\Entity\SettingInterface> $class */
        $class = $entityManager->getClassMetadata(TelemetryEvent::class);

        parent::__construct($entityManager, $class);

        $this->telemetryEventMapper = $telemetryEventMapper;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @return void
     */
    public function save(TelemetryEventInterface $telemetryEvent, bool $flush = true): void
    {
        $telemetryEvent = $this->telemetryEventMapper->mapToInfrastructureTelemetryEvent($telemetryEvent);

        $this->getEntityManager()->persist($telemetryEvent);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function update(TelemetryEventInterface $telemetryEvent, bool $flush = true): void
    {
        $id = $telemetryEvent->getId();

        if ($id === null) {
            return;
        }

        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent|null $infrastructureEntity */
        $infrastructureEntity = $this->find($id);

        if ($infrastructureEntity === null) {
            throw new RuntimeException('Unable to find telemetry event with id %s', $id);
        }

        $this->telemetryEventMapper->mapIncomingTelemetryEventToExistingTelemetryEvent($telemetryEvent, $infrastructureEntity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Telemetry\TelemetryEventsQueryCriteria $criteria
     *
     * @return array
     */
    public function getTelemetryEvents(TelemetryEventsQueryCriteria $criteria): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb = $this->createQueryBuilder('te');

        if ($criteria->getMaxAttemptsCount() !== null) {
            $qb->andWhere($expr->lt('te.synchronizationAttemptsCount', ':maxAttemptsCount'));
            $qb->setParameter('maxAttemptsCount', $criteria->getMaxAttemptsCount());
        }

        if ($criteria->getMaxSyncTimestamp() !== null) {
            $qb->andWhere(
                $expr->orX(
                    $expr->lt('te.lastSynchronisationTimestamp', ':maxSyncTimestamp'),
                    $expr->isNull('te.lastSynchronisationTimestamp'),
                ),
            );
            $qb->setParameter('maxSyncTimestamp', $criteria->getMaxSyncTimestamp());
        }

        if ($criteria->getLimit() !== null) {
            $qb->setMaxResults($criteria->getLimit());
        }

        $telemetryEvents = $qb->getQuery()->getResult();

        return array_map([$this->telemetryEventMapper, 'mapToDomainTelemetryEvent'], $telemetryEvents);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface $telemetryEvent
     * @param bool $flush
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function remove(TelemetryEventInterface $telemetryEvent, bool $flush = true): void
    {
        $telemetryEventId = $telemetryEvent->getId();

        if ($telemetryEventId === null) {
            return;
        }

        $infrastructureTelemetryEvent = $this->find($telemetryEventId);

        if ($infrastructureTelemetryEvent === null) {
            throw new RuntimeException(sprintf('Unable to find telemetry event wth id %s', $telemetryEventId));
        }

        $this->getEntityManager()->remove($infrastructureTelemetryEvent);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    public function removeTelemetryEvents(array $telemetryEvents): void
    {
        $telemetryEventIds = array_map(static function (TelemetryEventInterface $telemetryEvent): int {
             return (int)$telemetryEvent->getId();
        }, $telemetryEvents);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->delete(TelemetryEvent::class, 'te')
            ->where($qb->expr()->in('te.id', ':telemetryEventIds'))
            ->setParameters(['telemetryEventIds' => $telemetryEventIds])
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $maxAttemptsCount
     * @param \DateInterval $telemetryEventTtl
     *
     * @return void
     */
    public function removeAbandonedTelemetryEvents(int $maxAttemptsCount, DateInterval $telemetryEventTtl): void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->delete(TelemetryEvent::class, 'te')
            ->where($qb->expr()->gte('te.synchronizationAttemptsCount', ':maxAttemptsCount'))
            ->orWhere($qb->expr()->lt('te.triggeredAt', ':maxTriggeredTime'))
            ->setParameters([
                'maxAttemptsCount' => $maxAttemptsCount,
                'maxTriggeredTime' => (new DateTimeImmutable())->sub($telemetryEventTtl),
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @return void
     */
    public function flushAndClear(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }
}
