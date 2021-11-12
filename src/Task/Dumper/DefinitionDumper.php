<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Dumper;

use Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface;
use Sdk\Task\Dumper\Finder\DefinitionFinderInterface;
use Sdk\Task\Exception\TaskDefinitionNotFound;
use Sdk\Task\StrategyResolverInterface;
use Sdk\Task\ValueResolver\ValueResolverInterface;

class DefinitionDumper implements DefinitionDumperInterface
{
    /**
     * @var \Sdk\Task\Dumper\Finder\DefinitionFinderInterface
     */
    protected $definitionFinder;

    /**
     * @var \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

    /**
     * @var \Sdk\Task\ValueResolver\ValueResolverInterface
     */
    protected $valueResolver;

    /**
     * @var \Sdk\Task\StrategyResolverInterface
     */
    protected $strategyResolver;

    /**
     * @param \Sdk\Task\Dumper\Finder\DefinitionFinderInterface $definitionFinder
     * @param \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Sdk\Task\ValueResolver\ValueResolverInterface $valueResolver
     * @param \Sdk\Task\StrategyResolverInterface $strategyResolver
     */
    public function __construct(
        DefinitionFinderInterface $definitionFinder,
        ConfigurationLoaderInterface $configurationLoader,
        ValueResolverInterface $valueResolver,
        StrategyResolverInterface $strategyResolver
    ) {
        $this->definitionFinder = $definitionFinder;
        $this->configurationLoader = $configurationLoader;
        $this->valueResolver = $valueResolver;
        $this->strategyResolver = $strategyResolver;
    }

    /**
     * @throws \Sdk\Task\Exception\TaskDefinitionNotFound
     *
     * @return array
     */
    public function dump(): array
    {
        $taskDefinitions = [];
        $files = $this->definitionFinder->find();
        foreach ($files as $fileInfo) {
            $taskName = str_replace('.' . $fileInfo->getExtension(), '', $fileInfo->getFilename());
            $taskDefinitions[$taskName] = $this->configurationLoader->loadTask($taskName);
        }

        if (!$taskDefinitions) {
            throw new TaskDefinitionNotFound('Add paths to task definition');
        }

        ksort($taskDefinitions);

        return $taskDefinitions;
    }

    /**
     * @return array
     */
    public function dumpUniqueTaskPlaceholderNames(): array
    {
        $taskPlaceholders = [];
        $files = $this->definitionFinder->find();
        foreach ($files as $fileInfo) {
            $taskName = str_replace('.' . $fileInfo->getExtension(), '', $fileInfo->getFilename());
            $taskDefinition = $this->configurationLoader->loadTask($taskName);
            if (!empty($taskDefinition['placeholders'])) {
                foreach ($taskDefinition['placeholders'] as $placeholder) {
                    $taskPlaceholders[$placeholder['name']] = $placeholder['name'];
                }
            }
        }


        return array_keys($taskPlaceholders);
    }

    /**
     * @param string $taskName
     *
     * @return array
     */
    public function dumpTaskDefinition(string $taskName): array
    {
        $taskDefinition = $this->strategyResolver->resolve($this->configurationLoader->loadTask($taskName))->extract();
        $taskDefinition['placeholders'] = $this->valueResolver->expand($taskDefinition['placeholders']);

        return $taskDefinition;
    }
}
