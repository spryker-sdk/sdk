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
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context = null;

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
     * @param string|null $contextFilePath
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getContext(?string $contextFilePath = null): ContextInterface
    {
        if ($this->context === null) {
            if ($contextFilePath === null) {
                $this->context = new Context();

                return $this->context;
            }
            $this->context = $this->contextRepository->findByName($contextFilePath) ?: new Context();
        }

        return $this->context;
    }
}
