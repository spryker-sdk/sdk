<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction;

interface SdkUpdateActionInterface
{
    /**
     * @param array<string> $taskIds
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return void
     */
    public function apply(array $taskIds, array $tasksFromDirectories, array $tasksFromDatabase): void;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return array<string>
     */
    public function filter(array $tasksFromDirectories, array $tasksFromDatabase): array;
}
