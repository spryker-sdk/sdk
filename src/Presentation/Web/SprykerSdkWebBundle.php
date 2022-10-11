<?php

namespace SprykerSdk\Sdk\Presentation\Web;

use SprykerSdk\Sdk\Presentation\Web\DependencyInjection\SprykerSdkWebExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkWebBundle extends Bundle
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\Extension
     */
    public function createContainerExtension(): Extension
    {
        return new SprykerSdkWebExtension();
    }
}
