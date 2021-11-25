<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Repository;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

interface TaskSaveRepositoryInterface
{
    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function save(TaskInterface $task);
}
