<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation as DomainTaskSetRelation;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation as InfrastructureTaskSetRelation;

interface TaskSetTaskRelationMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $taskSetRelation
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\TaskSetTaskRelation
     */
    public function mapToInfrastructureTaskSetRelation(TaskSetTaskRelationInterface $taskSetRelation): InfrastructureTaskSetRelation;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $taskSetRelation
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation
     */
    public function mapToDomainTaskSetRelation(TaskSetTaskRelationInterface $taskSetRelation): DomainTaskSetRelation;
}
