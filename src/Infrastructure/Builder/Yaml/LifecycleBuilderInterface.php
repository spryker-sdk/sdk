<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;

interface LifecycleBuilderInterface
{
    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    public function buildLifecycle(array $taskData, array $taskListData, array $tags = []): TaskLifecycleInterface;
}
