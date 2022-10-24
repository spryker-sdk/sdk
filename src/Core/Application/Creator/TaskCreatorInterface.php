<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Creator;

use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;

interface TaskCreatorInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function createTasks(InitializeCriteriaDto $criteriaDto): InitializeResultDto;
}
