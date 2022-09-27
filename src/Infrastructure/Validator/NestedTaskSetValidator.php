<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator;

use SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class NestedTaskSetValidator
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException
     *
     * @return void
     */
    public function validate(TaskInterface $task): void
    {
        if ($task instanceof TaskSetInterface) {
            throw new TaskSetNestingException(sprintf(
                'Task set with id %s can\'t have another task set inside.',
                $task->getId(),
            ));
        }
    }
}
