<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetOverrideMap;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetOverrideMapDtoFactory
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto
     */
    public function createFromTaskSet(TaskSetInterface $taskSet): TaskSetOverrideMapDto
    {
        return new TaskSetOverrideMapDto(
            $taskSet->getStopOnErrorMap(),
            $taskSet->getTagsMap(),
            $taskSet->getSharedPlaceholdersMap(),
            $taskSet->getOverridePlaceholdersMap(),
        );
    }
}
