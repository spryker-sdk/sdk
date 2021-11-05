<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Configuration\Validator;

interface ConfigurationValidatorInterface
{
    /**
     * @param array $taskDefinition
     *
     * @return array
     */
    public function validate(array $taskDefinition): array;
}
