<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\SdkUpdateAction;

interface SdkUpdateActionInterface
{
    /**
     * @param string[] $taskIds
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return string[]
     */
    public function filter(array $folderTasks, array $databaseTasks): array;
}
