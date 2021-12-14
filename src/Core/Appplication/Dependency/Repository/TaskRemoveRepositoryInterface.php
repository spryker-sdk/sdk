<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskRemoveRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void;
}
