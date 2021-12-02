<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\CommandRunner;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;

interface CommandRunnerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    public function execute(CommandInterface $command, Context $context): Context;
}
