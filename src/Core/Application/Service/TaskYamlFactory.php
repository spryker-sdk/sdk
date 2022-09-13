<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;

class TaskYamlFactory implements TaskYamlFactoryInterface
{
    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface
     */
    public function createTaskYaml(array $taskData, array $taskListData, array $tasks = []): TaskYamlInterface
    {
        return new TaskYaml($taskData, $taskListData, $tasks);
    }
}
