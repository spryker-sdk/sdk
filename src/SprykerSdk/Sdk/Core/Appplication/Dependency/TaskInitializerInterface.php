<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface TaskInitializerInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface[]
     */
    public function initialize(): array;
}
