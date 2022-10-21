<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;

interface ApplicableInitializerInterface extends InitializerInterface
{
    public function isApplicable(InitializeCriteriaDto $criteriaDto): bool;
}
