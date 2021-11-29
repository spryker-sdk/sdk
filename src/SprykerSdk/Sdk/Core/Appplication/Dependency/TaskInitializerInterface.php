<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface TaskInitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $tasks
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[]
     */
    public function initialize(array $tasks): array;
}
