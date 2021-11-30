<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Placeholder;

class PlaceholderMapper implements PlaceholderMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Placeholder>
     */
    public function mapPlaceholder(PlaceholderInterface $placeholder): Placeholder
    {
        return new Placeholder(
            $placeholder->getName(),
            $placeholder->getValueResolver(),
            $placeholder->getConfiguration(),
            $placeholder->isOptional(),
        );
    }
}
