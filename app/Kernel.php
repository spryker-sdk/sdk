<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace App;

use SprykerSdk\Sdk\Infrastructure\Service\AutoloaderService;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
     *
     * @return void
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');


        if (is_file(dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import('../config/{services}.php');
        }
    }

    /**
     * @param \Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator $routes
     *
     * @return void
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } else {
            $routes->import('../config/{routes}.php');
        }
    }

    /**
     * @return iterable
     */
    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        $bundles = [];

        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                $bundles[$class] = new $class();
            }
        }

        $projectDir = $this->getProjectDir();
        $extensionDirectories = [
            sprintf('%s/extension/*/', $projectDir),
            sprintf('%s/extension/*/*/', $projectDir),
            sprintf('%s/extension/*/src/*/', $projectDir),
            sprintf('%s/extension/*/*/src/*/', $projectDir),
        ];

        $autoloader = new AutoloaderService($this->getProjectDir());
        $autoloader->loadClassesFromDirectory(
            $extensionDirectories,
            '*Bundle.php',
            function (string $loadableClassName) use (&$bundles): void {
                $bundles[$loadableClassName] = new $loadableClassName();
            }
        );

        return $bundles;
    }
}
