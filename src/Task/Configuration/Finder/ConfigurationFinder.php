<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Configuration\Finder;

use Sdk\Task\Exception\TaskDefinitionNotFound;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ConfigurationFinder implements ConfigurationFinderInterface
{
    /**
     * @var string[]
     */
    protected $directories;

    /**
     * @param string[] $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @param string $sprykName
     *
     * @throws \Sdk\Exception\TaskDefinitionNotFound
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function find(string $sprykName): SplFileInfo
    {
        $finder = $this->buildFinder($sprykName);

        if (!$finder->hasResults()) {
            throw new TaskDefinitionNotFound(sprintf('Could not find task definition for "%s"', $sprykName));
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        return $iterator->current();
    }

    /**
     * @param string $sprykName
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function buildFinder(string $sprykName): Finder
    {
        $fileName = sprintf('%s.yml', $sprykName);

        $finder = new Finder();
        $finder->in($this->directories)->name($fileName);

        return $finder;
    }
}
