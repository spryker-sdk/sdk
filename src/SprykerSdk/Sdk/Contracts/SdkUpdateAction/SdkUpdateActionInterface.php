<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\SdkUpdateAction;

interface SdkUpdateActionInterface
{
    /**
     * @param array<string> $taskIds
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $folderTasks
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $databaseTasks
     *
     * @return array<string>
     */
    public function filter(array $folderTasks, array $databaseTasks): array;
}
