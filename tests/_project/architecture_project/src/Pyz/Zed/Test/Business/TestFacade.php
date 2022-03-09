<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Test\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\Test\Business\TestBusinessFactory getFactory()
 */
class TestFacade extends AbstractFacade implements TestFacadeInterface
{
    /**
     * @return void
     */
    public function test(): void
    {
    }
}
