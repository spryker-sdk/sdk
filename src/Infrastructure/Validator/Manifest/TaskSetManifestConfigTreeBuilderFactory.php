<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigTreeBuilderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class TaskSetManifestConfigTreeBuilderFactory implements ManifestConfigTreeBuilderFactoryInterface
{
    /**
     * @var string
     */
    public const NAME = 'task-set';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ManifestEntriesValidator
     */
    protected ManifestEntriesValidator $validationHelper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ManifestEntriesValidator $validationHelper
     */
    public function __construct(ManifestEntriesValidator $validationHelper)
    {
        $this->validationHelper = $validationHelper;
    }

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
     * @param array $config
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder(array $config): TreeBuilder
    {
        $tree = new TreeBuilder($config['id'], 'array');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node */
        $node = $tree->getRootNode();
        $node->children()
                ->scalarNode('id')->end()
                ->scalarNode('help')->defaultNull()->end()
                ->scalarNode('stage')->end()
                ->scalarNode('version')->end()
                ->scalarNode('short_description')->end()
                ->scalarNode('command')->end()
                ->scalarNode('type')->end()
                ->arrayNode('placeholders')->end()
                ->arrayNode('stages')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
                ->arrayNode('shared_placeholders')
                        ->normalizeKeys(false)
                        ->useAttributeAsKey('name')
                        ->arrayPrototype()

                        ->children()
                            ->scalarNode('description')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $tasks = $node->children()
            ->arrayNode('tasks')
                ->arrayPrototype()
                    ->children();

        $tasks->scalarNode('id')->end()
            ->booleanNode('stop_on_error')->end()
            ->arrayNode('tags')
                ->useAttributeAsKey('name')
                ->prototype('variable')->end()
            ->end();

        $this->addPlaceholderDefinition(
            $tasks
                ->arrayNode('placeholder_overrides')
                ->arrayPrototype()
                ->normalizeKeys(false)
                    ->children(),
        );

        $node->validate()
            ->ifTrue(function (array $task) {
                $taskIds = [];
                $placeholderOverrideIds = [];
                $sharedPlaceholderIds = !empty($task['shared_placeholders']) ? array_keys($task['shared_placeholders']) : [];
                foreach ($task['tasks'] as $subTask) {
                    $taskIds[] = $subTask['id'];
                    if (!isset($placeholderOverrideIds[$subTask['id']])) {
                        $placeholderOverrideIds[$subTask['id']] = [];
                    }
                    if ($subTask['placeholder_overrides']) {
                        $placeholderOverrideIds[$subTask['id']] = array_keys($subTask['placeholder_overrides']);
                    }
                }

                $tasksPlaceholders = $this->validationHelper
                    ->getTaskPlaceholders(
                        $taskIds,
                    );

                $uniquePlaceholders = [];

                foreach ($tasksPlaceholders as $taskId => $taskPlaceholders) {
                    foreach ($taskPlaceholders as $taskPlaceholder) {
                        if (!isset($uniquePlaceholders[$taskPlaceholder])) {
                            $uniquePlaceholders[$taskPlaceholder] = true;

                            continue;
                        }
                        if (
                            in_array($taskPlaceholder, $sharedPlaceholderIds) ||
                            in_array($taskPlaceholder, $placeholderOverrideIds[$taskId])
                        ) {
                            continue;
                        }

                        return true;
                    }
                }

                return false;
            })
            ->thenInvalid('You have the same placeholder names in different tasks. You should resolve them.')
        ->end();

        return $tree;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\NodeBuilder $placeholder
     *
     * @return void
     */
    protected function addPlaceholderDefinition(NodeBuilder $placeholder): void
    {
        $placeholder
            ->scalarNode('name')
                ->isRequired()
                ->info('Placeholder has `/^%[a-zA-Z-_]+%$/` format.')
                ->validate()
                    ->ifTrue(function (string $value) {
                        return !preg_match('/^%[a-zA-Z-_]+%$/u', $value);
                    })
                    ->thenInvalid('Placeholder %s has invalid format.')
                ->end()
            ->end()
            ->scalarNode('optional')
                ->validate()
                    ->ifTrue(function (string $value) {
                        if (!$value) {
                            return false;
                        }

                        return !filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    })
                    ->thenInvalid('`%s` is\'t boolean type. Possible values: `true` or `false`.')
                ->end()
            ->end()
            ->arrayNode('configuration')
                ->children()
                    ->scalarNode('name')
                        ->setDeprecated()
                        ->defaultNull()
                    ->end()
                    ->scalarNode('option')
                      ->defaultNull()
                    ->end()
                    ->scalarNode('alias')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('description')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('help')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('type')
                        ->validate()
                            ->ifNotInArray($this->validationHelper->getSupportedTypes())
                            ->thenInvalid('Not supported `%s` type. Available types: ' . implode(',', $this->validationHelper->getSupportedTypes()))
                        ->end()
                    ->end()
                    ->scalarNode('defaultValue')
                        ->defaultNull()
                    ->end()
                    ->arrayNode('settingPaths')
                        ->useAttributeAsKey('name')
                        ->prototype('variable')->end()
                    ->end()
                    ->arrayNode('choiceValues')
                        ->useAttributeAsKey('name')
                        ->prototype('variable')->end()
                    ->end()
                    ->scalarNode('flag')->end();
    }
}
