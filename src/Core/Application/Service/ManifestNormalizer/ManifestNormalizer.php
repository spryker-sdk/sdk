<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\ManifestNormalizer;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestNormalizerInterface;
use Symfony\Component\Config\Definition\Processor;

class ManifestNormalizer implements ManifestNormalizerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ManifestNormalizer\ManifestNormaliserRegistry
     */
    public ManifestNormaliserRegistry $manifestValidatorFactory;

    /**
     * @var \Symfony\Component\Config\Definition\Processor
     */
    public Processor $processor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ManifestNormalizer\ManifestNormaliserRegistry $manifestValidatorFactory
     * @param \Symfony\Component\Config\Definition\Processor $processor
     */
    public function __construct(ManifestNormaliserRegistry $manifestValidatorFactory, Processor $processor)
    {
        $this->manifestValidatorFactory = $manifestValidatorFactory;
        $this->processor = $processor;
    }

    /**
     * @param string $type
     * @param array<array> $configs
     *
     * @return array<string, array>
     */
    public function validate(string $type, array $configs): array
    {
        $manifestValidator = $this->manifestValidatorFactory->resolve($type);

        foreach ($configs as $key => $config) {
            $configs[$key] = $this->processor->process(
                $manifestValidator->getConfigTreeBuilder($config)->buildTree(),
                [$config],
            );
        }

        return $configs;
    }
}
