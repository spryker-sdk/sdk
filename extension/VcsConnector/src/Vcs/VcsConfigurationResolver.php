<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs;

use Traversable;
use VcsConnector\Exception\AdapterDoesNotExistException;
use VcsConnector\Vcs\Adapter\VcsInterface;

class VcsConfigurationResolver implements VcsConfigurationResolverInterface
{
 /**
  * @var iterable<\VcsConnector\Vcs\Adapter\VcsInterface>
  */
    protected array $vcsAdapters = [];

    /**
     * @var \VcsConnector\Vcs\VcsProcessExecutor
     */
    protected VcsProcessExecutor $vcsProcessExecutor;

    /**
     * @param iterable $vcsAdapters
     * @param \VcsConnector\Vcs\VcsProcessExecutor $vcsProcessExecutor
     */
    public function __construct(iterable $vcsAdapters, VcsProcessExecutor $vcsProcessExecutor)
    {
        $this->vcsAdapters = $vcsAdapters instanceof Traversable
            ? iterator_to_array($vcsAdapters)
            : $vcsAdapters;

        $this->vcsProcessExecutor = $vcsProcessExecutor;
    }

    /**
     * @param string $vcs
     *
     * @throws \VcsConnector\Exception\AdapterDoesNotExistException
     *
     * @return \VcsConnector\Vcs\Adapter\VcsInterface
     */
    public function resolve(string $vcs): VcsInterface
    {
        if (!isset($this->vcsAdapters[$vcs])) {
            throw new AdapterDoesNotExistException(sprintf('`%s` csv adapter doesn\'t not exist.', $vcs));
        }

        return $this->vcsAdapters[$vcs];
    }
}
