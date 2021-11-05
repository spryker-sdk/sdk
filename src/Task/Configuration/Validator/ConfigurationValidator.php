<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Configuration\Validator;

class ConfigurationValidator implements ConfigurationValidatorInterface
{
    /**
     * @var \Sdk\Task\Configuration\Validator\Rules\ConfigurationValidatorRuleInterface[]
     */
    protected $rules;

    /**
     * @var string[]
     */
    protected $errorMessages = [];

    /**
     * @param \Sdk\Task\Configuration\Validator\Rules\ConfigurationValidatorRuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $taskDefinition
     *
     * @return array
     */
    public function validate(array $taskDefinition): array
    {
        foreach ($this->rules as $rule) {
            if (!$rule->validate($taskDefinition)) {
                $this->errorMessages[] = $rule->getErrorMessage();
            }
        }

        return $this->errorMessages;
    }
}
