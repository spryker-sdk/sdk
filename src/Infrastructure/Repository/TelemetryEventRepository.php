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
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TelemetryEvent;
use SprykerSdk\Sdk\Infrastructure\Mapper\TelemetryEventMapperInterface;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface;

class TelemetryEventRepository extends EntityRepository implements TelemetryEventRepositoryInterface
{
    private TelemetryEventMapperInterface $telemetryEventMapper;

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
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
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
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
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
        $infrastructureEntity = $this->find($telemetryEvent->getId());

        if ($infrastructureEntity === null) {
            throw new RuntimeException('Unable to find telemetry event with id %s', $id);
        }

        $this->telemetryEventMapper->mapTelemetryEvents($telemetryEvent, $infrastructureEntity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $maxAttemptsCount
     * @param int $limit
     * @param int $maxSyncTimestamp
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface>
     */
    public function getTelemetryEvents(int $maxAttemptsCount, int $limit, int $maxSyncTimestamp): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $telemetryEvents = $this->createQueryBuilder('te')
            ->where($expr->lt('te.synchronizationAttemptsCount', ':maxAttemptsCount'))
            ->andWhere(
                $expr->orX(
                    $expr->lt('te.lastSynchronisationTimestamp', ':maxSyncTimestamp'),
                    $expr->isNull('te.lastSynchronisationTimestamp'),
                ),
            )
            ->setMaxResults($limit)
            ->setParameters([
                'maxAttemptsCount' => $maxAttemptsCount,
                'maxSyncTimestamp' => $maxSyncTimestamp,
            ])
            ->getQuery()
            ->getResult();

        return array_map([$this->telemetryEventMapper, 'mapToDomainTelemetryEvent'], $telemetryEvents);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface $telemetryEvent
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
            ->orWhere($qb->expr()->lt('te.createdAt', ':maxCreatedTime'))
            ->setParameters([
                'maxAttemptsCount' => $maxAttemptsCount,
                'maxCreatedTime' => (new DateTimeImmutable())->sub($telemetryEventTtl),
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
