<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskYamlRepositoryInterface
{
    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function findAll();

    /**
     * Specific method for task validation
     *
     * @param string $taskId
     * @param bool $includeTaskSet
     *
     * @return bool
     */
    public function isTaskIdExist(string $taskId, bool $includeTaskSet = true): bool;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface;

    /**
     * Specific method for task set validation
     *
     * @param array<string> $taskIds
     * @param bool $includeTaskSet
     *
     * @return array<string, array<string>>
     */
    public function getTaskPlaceholders(array $taskIds, bool $includeTaskSet = false): array;
}
