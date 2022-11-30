<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Manifest\ValueResolver;

use SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver;

class StaticValueResolverMap extends OriginValueResolverMap
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return StaticValueResolver::RESOLVER_ID;
    }
}
