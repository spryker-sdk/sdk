<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;

interface ApplicableInitializerInterface extends InitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return bool
     */
    public function isApplicable(InitializeCriteriaDto $criteriaDto): bool;
}
