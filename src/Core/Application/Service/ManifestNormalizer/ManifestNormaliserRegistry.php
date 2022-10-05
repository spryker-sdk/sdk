<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\ManifestNormalizer;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ManifestValidatorMissingException;

class ManifestNormaliserRegistry
{
    /**
     * @var array<\SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface>
     */
    protected iterable $manifestValidators;

    /**
     * @param array<\SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface> $manifestValidators
     */
    public function __construct(iterable $manifestValidators)
    {
        $this->manifestValidators = $manifestValidators;
    }

    /**
     * @param string $entity
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\ManifestValidatorMissingException
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface
     */
    public function resolve(string $entity): ManifestConfigurationInterface
    {
        foreach ($this->manifestValidators as $manifestValidator) {
            if ($manifestValidator->getName() === $entity) {
                return $manifestValidator;
            }
        }

        throw new ManifestValidatorMissingException(sprintf('Can\'t resolve manifest validator by %s name', $entity));
    }
}
