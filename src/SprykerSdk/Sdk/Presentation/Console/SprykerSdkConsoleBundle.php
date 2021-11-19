<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console;

use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\DynamicConsoleCommandsCompilerPass;
use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\SprykerSdkConsoleExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkConsoleBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(
            new DynamicConsoleCommandsCompilerPass(),
            PassConfig::TYPE_BEFORE_REMOVING,
            -3
        );
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\Extension
     */
    public function createContainerExtension(): Extension
    {
        return new SprykerSdkConsoleExtension();
    }
}