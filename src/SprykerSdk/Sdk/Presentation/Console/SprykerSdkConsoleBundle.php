<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console;

use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\SprykerSdkConsoleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkConsoleBundle extends Bundle
{
    public function createContainerExtension()
    {
        return new SprykerSdkConsoleExtension();
    }
}