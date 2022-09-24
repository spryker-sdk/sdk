<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Factory;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class PlaceholderFactory
{
    /**
     * @param array<string, mixed> $placeholderData
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    public function createFromArray(array $placeholderData): PlaceholderInterface
    {
        return new Placeholder(
            $placeholderData['name'],
            $placeholderData['value_resolver'],
            $placeholderData['configuration'] ?? [],
            $placeholderData['optional'] ?? false,
        );
    }
}
