<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\TaskValidator;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class NestedTaskSetValidator implements TaskValidatorInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException
     *
     * @return bool
     */
    public function validate(TaskInterface $task): bool
    {
        if ($task instanceof TaskSetInterface) {
            throw new TaskSetNestingException(sprintf(
                'Task set with id %s can\'t have another task set inside.',
                $task->getId(),
            ));
        }

        return true;
    }
}
