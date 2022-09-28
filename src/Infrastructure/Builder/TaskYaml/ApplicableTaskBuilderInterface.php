<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;

interface ApplicableTaskBuilderInterface extends TaskBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return bool
     */
    public function isApplicable(TaskYamlCriteriaDto $taskYamlCriteriaDto): bool;
}
