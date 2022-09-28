<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\ManifestValidation;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidationInterface;
use Symfony\Component\Config\Definition\Processor;

class ManifestValidation implements ManifestValidationInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ManifestValidation\ManifestValidatorFactory
     */
    public ManifestValidatorFactory $manifestValidatorFactory;

    /**
     * @var \Symfony\Component\Config\Definition\Processor
     */
    public Processor $processor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ManifestValidation\ManifestValidatorFactory $manifestValidatorFactory
     * @param \Symfony\Component\Config\Definition\Processor $processor
     */
    public function __construct(ManifestValidatorFactory $manifestValidatorFactory, Processor $processor)
    {
        $this->manifestValidatorFactory = $manifestValidatorFactory;
        $this->processor = $processor;
    }

    /**
     * @param string $entity
     * @param array<string, array> $configs
     *
     * @return array<string, array>
     */
    public function validate(string $entity, array $configs): array
    {
        $manifestValidator = $this->manifestValidatorFactory->resolve($entity);

        foreach ($configs as $filePath => $config) {
            $configs[$filePath] = $this->processor->process(
                $manifestValidator->getConfigTreeBuilder($filePath)->buildTree(),
                [$config],
            );
        }

        return $configs;
    }
}
