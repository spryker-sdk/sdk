<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;

class ViolationConverterResolver
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface
     */
    public ConverterRegistryInterface $converterRegistry;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface $converterRegistry
     */
    public function __construct(ConverterRegistryInterface $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationConverterInterface|null
     */
    public function resolve(CommandInterface $command): ?ViolationConverterInterface
    {
        $converter = $command->getViolationConverter();

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
