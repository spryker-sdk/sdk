<?php

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
