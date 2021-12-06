<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Repository;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskRemoveRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void;
}
