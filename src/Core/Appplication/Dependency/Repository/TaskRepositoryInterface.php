<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskRepositoryInterface
{
    /**
     * @return array<string, \SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function findAll();

    /**
     * @param string $taskId
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId, array $tags = []): ?TaskInterface;
}
