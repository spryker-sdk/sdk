<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;

class ContextFactory implements ContextFactoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context = null;

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    public function getContext(): ContextInterface
    {
        if (!$this->context) {
            $this->context = new Context();
        }

        return $this->context;
    }
}
