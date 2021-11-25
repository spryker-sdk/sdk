<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\DependencyInjection;

use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DynamicConsoleCommandsCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('console.command_loader')) {
            $container->getDefinition('console.command_loader')
                ->setClass(TaskRunFactoryLoader::class)
                ->addArgument(new Reference('service_container'));
        }
    }
}
