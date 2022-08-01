<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use LogicException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ContextStorage
{
    protected ?ContextInterface $context = null;

    /**
     * @throws \LogicException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getContext(): ContextInterface
    {
        if ($this->context === null) {
            throw new LogicException(sprintf('Context is not set. Populate it firstly'));
        }

        return $this->context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function setContext(ContextInterface $context): void
    {
        $this->context = $context;
    }
}
