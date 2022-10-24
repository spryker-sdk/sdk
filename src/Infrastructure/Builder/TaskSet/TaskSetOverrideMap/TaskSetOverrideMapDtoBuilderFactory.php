<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap;

class TaskSetOverrideMapDtoBuilderFactory
{
    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilder
     */
    public function getBuilder(): TaskSetOverrideMapDtoBuilder
    {
        return new TaskSetOverrideMapDtoBuilder();
    }
}
