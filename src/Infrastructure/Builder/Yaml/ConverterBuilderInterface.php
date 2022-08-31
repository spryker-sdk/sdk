<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\SdkContracts\Entity\ConverterInterface;

interface ConverterBuilderInterface
{
    /**
     * @param array $data
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function buildConverter(array $data): ?ConverterInterface;
}
