<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Placeholder;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

interface PlaceholderMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder
     */
    public function mapPlaceholder(PlaceholderInterface $placeholder): Placeholder;
}
