<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Initializer;

use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;

class SdkInitializer implements InitializerInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Application\Dependency\ApplicableInitializerInterface>
     */
    protected iterable $concreteInitializers;

    /**
     * @param iterable<\SprykerSdk\Sdk\Core\Application\Dependency\ApplicableInitializerInterface> $concreteInitializers
     */
    public function __construct($concreteInitializers)
    {
        $this->concreteInitializers = $concreteInitializers;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function initialize(InitializeCriteriaDto $criteriaDto): InitializeResultDto
    {
        foreach ($this->concreteInitializers as $concreteInitializer) {
            if (!$concreteInitializer->isApplicable($criteriaDto)) {
                continue;
            }

            return $concreteInitializer->initialize($criteriaDto);
        }

        return $this->createFailedResultDto();
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    protected function createFailedResultDto(): InitializeResultDto
    {
        $resultDto = new InitializeResultDto();
        $resultDto->fail();

        return $resultDto;
    }
}
