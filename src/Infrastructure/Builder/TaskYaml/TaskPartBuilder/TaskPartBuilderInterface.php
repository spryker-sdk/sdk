<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;

interface TaskPartBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    public function addPart(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto;
}
