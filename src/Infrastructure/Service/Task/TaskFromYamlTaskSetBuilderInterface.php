<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskFromYamlTaskSetBuilderInterface
{
    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, mixed> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskFromYamlTaskSet(array $taskSetConfiguration, array $allTasksConfigurations, array $existingTasks): TaskInterface;
}
