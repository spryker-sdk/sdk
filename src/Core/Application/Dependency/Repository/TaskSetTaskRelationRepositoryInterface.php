<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface;

interface TaskSetTaskRelationRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface $relation
     *
     * @return void
     */
    public function create(TaskSetTaskRelationInterface $relation): void;

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface> $relations
     *
     * @return void
     */
    public function createMany(array $relations): void;

    /**
     * @param string $taskSetId
     *
     * @return void
     */
    public function removeByTaskSetId(string $taskSetId): void;

    /**
     * @param string $taskSetId
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function getByTaskSetId(string $taskSetId): array;
}
