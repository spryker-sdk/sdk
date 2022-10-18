<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function build(TaskYamlCriteriaDto $taskYamlCriteriaDto): TaskInterface;
}
