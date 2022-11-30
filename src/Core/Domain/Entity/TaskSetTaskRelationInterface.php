<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskSetTaskRelationInterface
{
    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTaskSet(): TaskInterface;

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getSubTask(): TaskInterface;
}
