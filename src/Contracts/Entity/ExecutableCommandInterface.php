<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface ExecutableCommandInterface extends CommandInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface;
}
