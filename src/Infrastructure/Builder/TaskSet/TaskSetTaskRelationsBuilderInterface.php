<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet;

use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

interface TaskSetTaskRelationsBuilderInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function buildFromTaskSet(TaskSetInterface $taskSet, array $existingTasks): array;
}
