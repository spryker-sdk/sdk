<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\ConverterInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Converter;

interface ConverterMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ConverterInterface $converter
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Converter
     */
    public function mapConverter(ConverterInterface $converter): Converter;
}
