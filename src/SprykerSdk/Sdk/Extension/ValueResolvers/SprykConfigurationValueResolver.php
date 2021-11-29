<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

class SprykConfigurationValueResolver extends StaticValueResolver
{
    /**
     * @var string
     */
    protected const PLACEHOLDER_MODE_NAME = '%mode%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_MODE_VALUE = 'project';

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'SPRYK_CONFIGURATION';
    }

    /**
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $resolvedValues = []): array
    {
        if (!isset($resolvedValues[static::PLACEHOLDER_MODE_NAME])) {
            return $this->choiceValues;
        }
        $mode = $resolvedValues[static::PLACEHOLDER_MODE_NAME];

        if ($mode === static::PLACEHOLDER_MODE_VALUE) {
            return [$this->getDefaultValue()];
        }

        $choiceValues = $this->choiceValues;

        unset($choiceValues[array_search($this->getDefaultValue(), $choiceValues)]);
        $this->value = reset($choiceValues);

        return $choiceValues;
    }
}
