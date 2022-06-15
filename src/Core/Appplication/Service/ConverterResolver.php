<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Report\ReportConverterInterface;

class ConverterResolver
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface
     */
    protected ConverterRegistryInterface $converterRegistry;

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
     * @return \SprykerSdk\SdkContracts\Report\ReportConverterInterface|null
     */
    public function resolve(CommandInterface $command): ?ReportConverterInterface
    {
        $converter = $command->getConverter();

        if (!$converter || !$this->converterRegistry->has($converter->getName())) {
            return null;
        }

        $reportConverter = $this->converterRegistry->get($converter->getName());

        if ($reportConverter === null) {
            return null;
        }

        $reportConverter->configure($converter->getConfiguration());

        return $reportConverter;
    }
}
