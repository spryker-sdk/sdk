<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Repository;

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
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface;
}
