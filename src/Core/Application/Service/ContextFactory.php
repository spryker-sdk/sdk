<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ContextFactory
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $contextRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface $contextRepository
     */
    public function __construct(ContextRepositoryInterface $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    /**
     * @param string|null $contextName
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getContext(?string $contextName = null): ContextInterface
    {
        if (!$contextName) {
            return new Context();
        }

        return $this->contextRepository->findByName($contextName) ?: new Context();
    }
}
