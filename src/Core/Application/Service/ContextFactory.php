<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\DefaultContextReceiverInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;

class ContextFactory implements ContextFactoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context = null;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\DefaultContextReceiverInterface
     */
    private DefaultContextReceiverInterface $defaultContextReceiver;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\DefaultContextReceiverInterface $defaultContextReceiver
     */
    public function __construct(DefaultContextReceiverInterface $defaultContextReceiver)
    {
        $this->defaultContextReceiver = $defaultContextReceiver;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    public function getContext(): ContextInterface
    {
        if (!$this->context) {
            $this->context = new Context();

            $this->context->setFormat($this->defaultContextReceiver->getFormat());
        }

        return $this->context;
    }
}
