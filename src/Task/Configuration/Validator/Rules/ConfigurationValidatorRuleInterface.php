<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Configuration\Validator\Rules;

interface ConfigurationValidatorRuleInterface
{
    /**
     * @param array $taskDefinition
     *
     * @return bool
     */
    public function validate(array $taskDefinition): bool;

    /**
     * @return string
     */
    public function getErrorMessage(): string;
}
