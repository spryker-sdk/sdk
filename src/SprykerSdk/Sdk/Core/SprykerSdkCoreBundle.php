<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core;

use SprykerSdk\Sdk\Core\Appplication\DependencyInjection\SprykerSdkCoreExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkCoreBundle extends Bundle
{
    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\DependencyInjection\SprykerSdkCoreExtension
     */
    protected function createContainerExtension(): SprykerSdkCoreExtension
    {
        return new SprykerSdkCoreExtension();
    }
}
