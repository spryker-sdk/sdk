<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation;

use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

interface TaskSetTaskRelationFacadeInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return void
     */
    public function collectTaskSet(TaskSetInterface $taskSet, array $existingTasks): void;

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return void
     */
    public function collectYamlTaskSet(array $taskSetConfiguration, array $existingTasks): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function createRelations(TaskInterface $task): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function removeRelations(TaskInterface $task): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function updateRelations(TaskInterface $task): void;
}
