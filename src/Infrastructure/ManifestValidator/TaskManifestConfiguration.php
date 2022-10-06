<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\ManifestValidator;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\Sdk\Core\Domain\Enum\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Enum\Task;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class TaskManifestConfiguration implements ManifestConfigurationInterface
{
    /**
     * @var string
     */
    public const NAME = 'task';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\ManifestValidator\ManifestEntriesValidator
     */
    protected ManifestEntriesValidator $validationHelper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\ManifestValidator\ManifestEntriesValidator $validationHelper
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
        $node = $tree->getRootNode();
        $node
            ->children()
                ->scalarNode('id')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return !preg_match('/^[a-z-]+(:[a-z-]+)+$/u', $value);
                        })
                        ->thenInvalid('Task id `%s` should have `/^[a-z-]+:[a-z-]+:[a-z-])+$/` format.')
                    ->end()
                ->end()
                ->scalarNode('stage')->isRequired()->end()
                ->arrayNode('tags')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
                ->scalarNode('successor')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(function (string $value) {
                            return !$value || !$this->validationHelper->isTaskNameExist($value);
                        })
                        ->thenInvalid('Task %s doesn\'t exist.')
                    ->end()
                ->end()
                ->scalarNode('deprecated')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(function (string $value) {
                            return $value && !filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        })
                        ->thenInvalid('`%s` is\'t boolean type. Possible values: `true` or `false`.')
                    ->end()
                ->end()
                ->scalarNode('optional')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(function (string $value) {
                            return $value && !filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        })
                        ->thenInvalid('`%s` is\'t boolean type. Possible values: `true` or `false`.')
                    ->end()
                ->end()
                ->scalarNode('version')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return !preg_match('/^\d+.\d+.\d+$/u', $value);
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
                ->scalarNode('command')
                    ->isRequired()
                    ->validate()
                        ->ifEmpty()
                        ->thenInvalid('Task command is require.')
                    ->end()
                ->end()
                ->scalarNode('help')
                    ->defaultNull()
                ->end()
                ->scalarNode('type')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray([Task::TASK_TYPE_LOCAL_CLI, Task::TASK_TYPE_LOCAL_CLI_INTERACTIVE])
                        ->thenInvalid(
                            vsprintf(
                                'Task type `%s` should have %s or %s.',
                                ['%s', Task::TASK_TYPE_LOCAL_CLI, Task::TASK_TYPE_LOCAL_CLI_INTERACTIVE],
                            ),
                        )
                    ->end()
                ->end()
                ->arrayNode('report_converter')
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(
                                    function ($name) {
                                        return !$this->validationHelper->isConverterValid($name);
                                    },
                                )
                                ->thenInvalid('Converter name `%s` doesn\'t exist.')
                            ->end()
                        ->end()
                        ->arrayNode('configuration')
                            ->children()
                                ->scalarNode('input_file')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('producer')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(function (array $task) {
                    return !$this->validationHelper
                        ->isPlaceholderInStringValid(
                            $task['command'],
                            array_map(function (array $placeholder) {
                                return $placeholder['name'];
                            }, $task['placeholders']),
                        );
                })
                ->thenInvalid('Not all placeholders uses.')
            ->end();

        $this->addPlaceholderDefinition(
            $node
            ->children()
                ->arrayNode('placeholders')
                ->arrayPrototype()
                    ->children(),
        );
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
                        ->ifNotInArray([Task::TASK_TYPE_LOCAL_CLI, Task::TASK_TYPE_LOCAL_CLI_INTERACTIVE])
                        ->thenInvalid(
                            vsprintf(
                                'Task type `%s` should have %s or %s.',
                                ['%s', Task::TASK_TYPE_LOCAL_CLI, Task::TASK_TYPE_LOCAL_CLI_INTERACTIVE],
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
                    ->ifTrue(function (string $value) {
                        return !preg_match('/^%[a-zA-Z-_]+%$/u', $value);
                    })
                    ->thenInvalid('Placeholder %s has invalid format.')
                ->end()
            ->end()
            ->scalarNode('value_resolver')
                ->isRequired()
                ->validate()
                    ->ifString()
                    ->ifTrue(function (string $placeholder) {
                        return !$this->validationHelper->isNameValid($placeholder);
                    })
                    ->thenInvalid('`%s` placeholder doesn\'t exist.')
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
