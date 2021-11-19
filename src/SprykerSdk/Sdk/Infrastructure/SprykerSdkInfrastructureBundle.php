<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure;

use SprykerSdk\Sdk\Infrastructure\DependencyInjection\SprykerSdkInfrastructureExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkInfrastructureBundle extends Bundle
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\Extension
     */
    public function createContainerExtension(): Extension
    {
        return new SprykerSdkInfrastructureExtension();
    }
}