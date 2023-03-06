<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc;

use InspectionDoc\DependencyInjection\InspectionDocExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InspectionDocBundle extends Bundle
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\Extension
     */
    public function createContainerExtension(): Extension
    {
        return new InspectionDocExtension();
    }
}
