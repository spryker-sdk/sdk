<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface;

class ViolationConverterResolver
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface
     */
    protected ConverterRepositoryInterface $converterRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface
     */
    public ConverterRegistryInterface $converterRegistry;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface $converterRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface $converterRegistry
     */
    public function __construct(ConverterRepositoryInterface $converterRepository, ConverterRegistryInterface $converterRegistry)
    {
        $this->converterRepository = $converterRepository;
        $this->converterRegistry = $converterRegistry;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface|null
     */
    public function resolve(CommandInterface $command): ?ViolationConverterInterface
    {
        $converter = $this->converterRepository->getConverter($command);

        if (!$converter || !$this->converterRegistry->has($converter->getName())) {
            return null;
        }

        $violationConverter = $this->converterRegistry->get($converter->getName());

        if (!$violationConverter) {
            return null;
        }
        $violationConverter->configure($converter->getConfiguration());

        return $violationConverter;
    }
}
