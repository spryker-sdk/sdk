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
     * @param string $name
     *
     * @return bool
     */
    public function isTaskNameExist(string $name): bool;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface;
}
