<?php

namespace EvaluatorExtension\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EvaluatorExtensionExtension extends Extension
{
    /**
    * @param array $configs
    * @param ContainerBuilder $container
    * @return void
    * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->createYamlFileLoader($container)->load('services.yaml');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \Symfony\Component\DependencyInjection\Loader\YamlFileLoader
     */
    protected function createYamlFileLoader(ContainerBuilder $container): YamlFileLoader
    {
        return new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    }
}
