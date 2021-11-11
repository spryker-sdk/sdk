<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface;
use Sdk\Task\ValueResolver\ValueResolverInterface;

class TaskSetTypeStrategy  extends AbstractTypeStrategy
{
    /**
     * @var \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected ConfigurationLoaderInterface $configurationLoader;

    /**
     * @var \Sdk\Task\ValueResolver\ValueResolverInterface
     */
    protected ValueResolverInterface $valueResolver;

    /**
     * @param \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Sdk\Task\ValueResolver\ValueResolverInterface $valueResolver
     */
    public function __construct(ConfigurationLoaderInterface $configurationLoader, ValueResolverInterface $valueResolver)
    {
        $this->configurationLoader = $configurationLoader;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'task_set';
    }

    /**
     * @return array
     */
    public function extract(): array
    {
        $this->definition['placeholders'] = [];
        if ($this->definition['tasks']) {
            foreach ($this->definition['tasks'] as $task) {
                $definition = $this->configurationLoader->loadTask($task['id']);
                if ($definition['placeholders'] && is_array($definition['placeholders'])) {
                    $this->definition['placeholders'] += $definition['placeholders'];
                }
            }
        }

        $this->definition = $this->valueResolver->expand($this->definition);

        return $this->definition;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        // task strategy execution
    }
}
