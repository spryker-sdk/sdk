<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Repository;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskRepositoryInterface
{
    /**
     * @return array<string, \SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function findAll();

    /**
     * @param string $taskId
     *
     * @return TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface;
}
