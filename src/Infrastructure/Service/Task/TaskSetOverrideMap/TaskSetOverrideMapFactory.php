<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap;

use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetOverrideMapFactory
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap
     */
    public function createFromTaskSet(TaskSetInterface $taskSet): TaskSetOverrideMap
    {
        return new TaskSetOverrideMap(
            $taskSet->getStopOnErrorMap(),
            $taskSet->getTagsMap(),
            $taskSet->getSharedPlaceholdersMap(),
            $taskSet->getOverridePlaceholdersMap(),
        );
    }
}
