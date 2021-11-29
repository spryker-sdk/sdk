<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;

class TaskDeprecatedAction implements SdkUpdateActionInterface
{

    /**
     * @param string[] $taskIds
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void
    {
        // TODO: Implement apply() method.
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return string[]
     */
    public function filter(array $folderTasks, array $databaseTasks): array
    {
        $taskIds = [];

        foreach ($databaseTasks as $databaseTask) {
            $folderTask = $folderTasks[$databaseTask->getId()] ?? null;

            if ($folderTask !== null && $folderTask->isDeprecated()) {
                $taskIds[] = $folderTask->getId();
            }
        }

        return $taskIds;
    }
}
