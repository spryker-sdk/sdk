<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskValidatorInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskValidationException
     *
     * @return bool
     */
    public function validate(TaskInterface $task): bool;
}
