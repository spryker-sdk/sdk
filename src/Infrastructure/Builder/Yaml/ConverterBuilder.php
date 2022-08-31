<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;

class ConverterBuilder implements ConverterBuilderInterface
{
    /**
     * @param array $data
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function buildConverter(array $data): ?ConverterInterface
    {
        if (!isset($data['report_converter'])) {
            return null;
        }

        return new Converter(
            $data['report_converter']['name'],
            $data['report_converter']['configuration'],
        );
    }
}
