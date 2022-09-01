<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\ConfigurableAbstractValueResolver;

class StaticValueResolver extends ConfigurableAbstractValueResolver
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'STATIC';
    }
}
