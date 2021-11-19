<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;

interface TaskRepositoryInterface
{
    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Task>
     */
    public function findAll(): array;

    /**
     * @param string $taskId
     *
     * @return TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface;
}