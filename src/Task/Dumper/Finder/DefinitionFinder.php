<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Dumper\Finder;

use Symfony\Component\Finder\Finder;

class DefinitionFinder implements DefinitionFinderInterface
{
    /**
     * @var string[]
     */
    protected $taskDirectories;

    /**
     * @param string[] $taskDirectories
     */
    public function __construct(array $taskDirectories)
    {
        $this->taskDirectories = $taskDirectories;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    public function find(): iterable
    {
        $finder = new Finder();
        $finder->in($this->taskDirectories)->files();

        return $finder;
    }
}
