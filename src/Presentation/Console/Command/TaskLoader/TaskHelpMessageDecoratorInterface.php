<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command\TaskLoader;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskHelpMessageDecoratorInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return string
     */
    public function decorateHelpMessage(TaskInterface $task): string;
}
