<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\SdkContracts\Enum\Lifecycle;
use SprykerSdk\SdkContracts\Enum\Task;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class TaskSetManifestConfiguration implements ManifestConfigurationInterface
{
    /**
     * @var string
     */
    public const NAME = 'task_set';

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
                ->scalarNode('id')
                    ->isRequired()
                        ->validate()
                        ->ifTrue(function ($value) {
                            return !preg_match('/^[a-z-]+:[a-z-]+:[a-z-]+$/u', (string)$value);
                        })
                        ->thenInvalid('Task id `%s` should have `/^[a-z-]+:[a-z-]+:[a-z-])+$/` format.')
                    ->end()
                ->end()
                ->scalarNode('help')->defaultNull()->end()
                ->scalarNode('stage')->isRequired()->end()
                ->scalarNode('version')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return !preg_match('/^\d+.\d+.\d+$/u', (string)$value);
                        })
                        ->thenInvalid('Task version `%s` should have `/^\d+.\d+.\d+$/` format.')
                    ->end()
                ->end()
                ->scalarNode('short_description')
                    ->isRequired()
                    ->validate()
                        ->ifEmpty()
                        ->thenInvalid('Task short description is require.')
                    ->end()
                ->end()
                ->scalarNode('command')->defaultNull()->end()
                ->scalarNode('type')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray([Task::TYPE_TASK_SET])
                        ->thenInvalid(
                            vsprintf(
                                'Task type `%s` should have %s.',
                                ['%s', Task::TYPE_TASK_SET],
                            ),
                        )
                    ->end()
                ->end()
                ->scalarNode('optional')
                    ->validate()
                        ->ifTrue(function ($value) {
                            return $value && !filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        })
                        ->thenInvalid('`%s` is\'t boolean type. Possible values: `true` or `false`.')
                    ->end()
                ->end()
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

        $tasks->scalarNode('id')
                ->validate()
                    ->ifTrue(function ($taskId) {
                        return !$taskId || !$this->validationHelper->isTaskIdExist((string)$taskId);
                    })
                    ->thenInvalid('Sub-task `%s` doesn\'t exist in tasks.')
                ->end()
            ->end()
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
            ->ifTrue(fn (array $task): bool => !$this->validationHelper->isPlaceholderExists($task))
            ->thenInvalid('You have the same placeholder names in different tasks. You should resolve them.')
        ->end();

        $this->addLifecycleDefinition($node
            ->children()
                ->arrayNode('lifecycle')
                ->children());

        return $tree;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\NodeBuilder $lifecycle
     *
     * @return void
     */
    protected function addLifecycleDefinition(NodeBuilder $lifecycle): void
    {
        foreach ([Lifecycle::EVENT_INITIALIZED, Lifecycle::EVENT_UPDATED, Lifecycle::EVENT_REMOVED] as $type) {
            $event = $lifecycle->arrayNode($type);
            $event->children()
                ->arrayNode('files')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('path')->end()
                        ->scalarNode('content')->end();

            $this->addPlaceholderDefinition(
                $event->children()
                    ->arrayNode('placeholders')
                        ->arrayPrototype()
                            ->children(),
            );
            $event->children()
                ->arrayNode('commands')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('command')
                        ->isRequired()
                        ->validate()
                            ->ifEmpty()
                            ->thenInvalid('Task command is require.')
                        ->end()
                    ->end()
                    ->scalarNode('type')
                        ->isRequired()
                        ->validate()
                            ->ifNotInArray([Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE, Task::TYPE_TASK_SET])
                            ->thenInvalid(
                                vsprintf(
                                    'Task type `%s` should have %s, %s or %s.',
                                    ['%s', Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE, Task::TYPE_TASK_SET],
                                ),
                            )
                        ->end()
                    ->end();
        }
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
                    ->ifTrue(function ($value) {
                        return !preg_match('/^%[a-zA-Z-_]+%$/u', (string)$value);
                    })
                    ->thenInvalid('Placeholder %s has invalid format.')
                ->end()
            ->end()
            ->scalarNode('optional')
                ->validate()
                    ->ifTrue(function ($value) {
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
