<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Manifest\ValueResolver;

use SprykerSdk\Sdk\Extension\ValueResolver\SdkDirectoryValueResolver;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValueResolverMapInterface;

class SdkDirectoryValueResolverMap implements ValueResolverMapInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return SdkDirectoryValueResolver::RESOLVER_ID;
    }

    /**
     * @return array<mixed, \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    public function getMap(): array
    {
        return [];
    }
}
