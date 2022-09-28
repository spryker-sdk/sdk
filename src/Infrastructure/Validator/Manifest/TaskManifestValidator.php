<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class TaskManifestValidator implements ManifestValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'task';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ValidationHelper
     */
    protected ValidationHelper $placeholderValidationHelper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ValidationHelper $placeholderValidationHelper
     */
    public function __construct(ValidationHelper $placeholderValidationHelper)
    {
        $this->placeholderValidationHelper = $placeholderValidationHelper;
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
     * @param string $entityName
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder(string $entityName): TreeBuilder
    {
        $tree = new TreeBuilder($entityName, 'array');
        $node = $tree->getRootNode();
        $node
            ->children()
                ->scalarNode('id')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return !preg_match('/^[a-z-]+:[a-z-]+:[a-z-]+$/u', $value);
                        })
                        ->thenInvalid('Task id `%s` should have `/^[a-z-]+:[a-z-]+:[a-z-]+$/` format.')
                    ->end()
                ->end()
                ->scalarNode('stage')->isRequired()->end()
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
                        ->ifNotInArray(['local_cli', 'local_cli_interactive'])
                        ->thenInvalid('Task should have local_cli_interactive or local_cli type.')
                    ->end()
                ->end()
                ->arrayNode('report_converter')
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(
                                    function ($name) {
                                        return !$this->placeholderValidationHelper->validateConverter($name);
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
                ->arrayNode('placeholders')
                    ->arrayPrototype()
                        ->children()
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
                                        return !$this->placeholderValidationHelper->validateName($placeholder);
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
                                    ->scalarNode('alias')
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
                                    ->scalarNode('type')
                                        ->validate()
                                            ->ifNotInArray($this->placeholderValidationHelper->getSupportedTypes())
                                            ->thenInvalid('Not supported `%s` type. Available types: ' . implode(',', $this->placeholderValidationHelper->getSupportedTypes()))
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
                                    ->scalarNode('flag')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->validate()
            ->ifTrue(function (array $task) {
                return !$this->placeholderValidationHelper
                    ->validatePlaceholderInCommand(
                        $task['command'],
                        array_map(function (array $placeholder) {
                            return $placeholder['name'];
                        }, $task['placeholders']),
                    );
            })
            ->thenInvalid('Not all placeholders uses.')
            ->end();

        return $tree;
    }
}
