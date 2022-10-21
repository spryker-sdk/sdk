<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Adapter;

use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use Throwable;

class Initializer
{
    protected InitializerInterface $initializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface $initializer
     */
    public function __construct(InitializerInterface $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * @param array $params
     *
     * @return bool is successful
     */
    public function initialize(array $params): bool
    {
        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_REST_API, $params);

        $resultDto = $this->initializer->initialize($criteriaDto);

        return $resultDto->isSuccessful();
    }
}
