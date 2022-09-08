<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\TaskYaml;

interface TaskYamlInterface
{
    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getTasks(): array;

    /**
     * @return array<string, mixed>
     */
    public function getTaskListData(): array;

    /**
     * @return array
     */
    public function getTaskData(): array;

    /**
     * @param array $taskData
     *
     * @return $this
     */
    public function withTaskData(array $taskData);
}
