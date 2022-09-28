<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Yaml\Yaml;

class TaskSetManifestValidator implements ManifestValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'task-set';

    /**
     * Returns manifest name.
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * Returns entities configuration.
     *
     * @return array<string, mixed>
     */
    public function getEntities(): array
    {
        return [
            'AcpAppAsyncApiCreateTaskSet' => Yaml::parseFile('/home/veselov/PhpstormProjects/spryker-sdk/src/Extension/Resources/config/task/AcpAppAsyncApiCreateTaskSet.yaml'),
        ];
    }

    /**
     * @param string $fileName
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder(string $fileName): TreeBuilder
    {
        $tree = new TreeBuilder('manifest', 'array');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node */
        $node = $tree->getRootNode();
        $node->children()
            ->scalarNode('id')->end()
            ->scalarNode('stage')->end()
            ->scalarNode('version')->end()
            ->scalarNode('short_description')->end()
            ->scalarNode('command')->end()
            ->scalarNode('type')->end()
            ->arrayNode('tasks')
            ->prototype('array')
            ->children()
            ->scalarNode('id')->end()
            ->booleanNode('stop_on_error')->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('placeholders')->end()
            ->scalarNode('id')->end()
            ->scalarNode('help')->defaultNull()->end()
            ->end();

        return $tree;
    }
}
