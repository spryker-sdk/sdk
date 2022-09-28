<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator;

class ConverterInputDataValidator
{
    /**
     * @param array $inputData
     *
     * @return bool
     */
    public function isValid(array $inputData): bool
    {
        if (!array_key_exists('report_converter', $inputData)) {
            return false;
        }

        $converterData = $inputData['report_converter'];

        if (!is_array($converterData)) {
            return false;
        }

        if (!array_key_exists('name', $converterData) || !is_string($converterData['name'])) {
            return false;
        }

        if (!array_key_exists('configuration', $converterData) || !is_array($converterData['configuration'])) {
            return false;
        }

        return true;
    }
}
