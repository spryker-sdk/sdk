<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class PlaceholderResolverHelper extends Module
{
    /**
     * @param string $name
     * @param string $valueResolverId
     * @param bool $isOptional
     * @param array $configuration
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    public function createPlaceholder(string $name, string $valueResolverId, bool $isOptional, array $configuration = []): PlaceholderInterface
    {
        return new Placeholder(
            $name,
            $valueResolverId,
            $configuration,
            $isOptional,
        );
    }
}
