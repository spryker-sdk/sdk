<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigTreeBuilderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

abstract class AbstractTaskManifestConfigTreeBuilderFactory implements ManifestConfigTreeBuilderFactoryInterface
{
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
