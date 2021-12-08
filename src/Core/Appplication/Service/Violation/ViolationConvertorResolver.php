<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface;

class ViolationConvertorResolver
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface
     */
    protected ConverterRepositoryInterface $converterRepository;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface> c
     */
    public array $violationConverters;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface $converterRepository
     * @param array<\SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface> $violationConverters
     */
    public function __construct(ConverterRepositoryInterface $converterRepository, array $violationConverters)
    {
        $this->converterRepository = $converterRepository;
        $this->violationConverters = $violationConverters;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface|null
     */
    public function resolve(CommandInterface $command): ?ViolationConverterInterface
    {
        $converter = $this->converterRepository->getConverter($command);

        if (!$converter) {
            return null;
        }

        foreach ($this->violationConverters as $violationConverter) {
            if (get_class($violationConverter) === $converter->getName()) {
                $violationConverter->configure($converter->getConfiguration());

                return $violationConverter;
            }
        }

        return null;
    }
}
