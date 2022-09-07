<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap;

class TaskSetOverrideMapBuilderFactory
{
    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilder
     */
    public function getBuilder(): TaskSetOverrideMapBuilder
    {
        return new TaskSetOverrideMapBuilder();
    }
}
