<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowEventRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Entity\WorkflowEvent;
use SprykerSdk\SdkContracts\Entity\WorkflowEventInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\WorkflowEvent>
 */
class WorkflowEventRepository extends ServiceEntityRepository implements WorkflowEventRepositoryInterface
{
    /**
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowEvent::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     * @param string|null $transition
     * @param array $events
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\WorkflowEventInterface>
     */
    public function searchByWorkflow(
        WorkflowInterface $workflow,
        ?string $transition = null,
        array $events = []
    ): array {
        $filter = [];

        if ($workflow instanceof Workflow) {
            $filter['workflow'] = $workflow->getId();
        }

        if ($transition) {
            $filter['transition'] = $transition;
        }

        if ($events) {
            $filter['event'] = $events;
        }

        return $this->findBy($filter, ['time' => 'asc']);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface $event
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowEventInterface
     */
    public function save(WorkflowEventInterface $event): WorkflowEventInterface
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();

        return $event;
    }
}
