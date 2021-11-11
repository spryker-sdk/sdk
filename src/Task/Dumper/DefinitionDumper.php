<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Dumper;

use Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface;
use Sdk\Task\Dumper\Finder\DefinitionFinderInterface;
use Sdk\Task\Exception\TaskDefinitionNotFound;

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
     * @param \Sdk\Task\Dumper\Finder\DefinitionFinderInterface $definitionFinder
     * @param \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(DefinitionFinderInterface $definitionFinder, ConfigurationLoaderInterface $configurationLoader)
    {
        $this->definitionFinder = $definitionFinder;
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * @param int|null $level
     *
     * @throws \Sdk\Task\Exception\TaskDefinitionNotFound
     *
     * @return array
     */
    public function dump(?int $level = null): array
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
}
