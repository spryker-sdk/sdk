<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ContextFactory
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context = null;

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getContext(): ContextInterface
    {
        if (!$this->context) {
            $this->context = new Context();
        }

        return $this->context;
    }
}
