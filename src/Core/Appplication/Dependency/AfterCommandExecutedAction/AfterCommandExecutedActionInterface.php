<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\AfterCommandExecutedAction;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface AfterCommandExecutedActionInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context, TaskInterface $task): ContextInterface;
}
