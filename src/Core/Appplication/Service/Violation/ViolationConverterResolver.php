<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;

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
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface|null
     */
    public function resolve(CommandInterface $command): ?ViolationConverterInterface
    {
        $converter = $command->getConverter();

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
