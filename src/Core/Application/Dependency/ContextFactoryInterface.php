<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface ContextFactoryInterface
{
    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getContext(): ContextInterface;
}
