<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure;

use SprykerSdk\Sdk\Infrastructure\DependencyInjection\SprykerSdkInfrastructureExtension;
use SprykerSdk\Sdk\Infrastructure\DependencyInjection\TaggedClassNameCollectionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SprykerSdkInfrastructureBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TaggedClassNameCollectionCompilerPass());
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\Extension
     */
    public function createContainerExtension(): Extension
    {
        return new SprykerSdkInfrastructureExtension();
    }
}
