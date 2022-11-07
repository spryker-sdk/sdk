<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs;

use Traversable;
use VcsConnector\Exception\AdapterDoesNotExist;
use VcsConnector\Vcs\Adapter\VcsInterface;

class VcsConfigurationResolver implements VcsConfigurationResolverInterface
{
 /**
  * @var iterable<\VcsConnector\Vcs\Adapter\VcsInterface>
  */
    protected array $vcsAdapters = [];

    /**
     * @param iterable<\VcsConnector\Vcs\Adapter\VcsInterface> $vcsAdapters
     */
    public function __construct(iterable $vcsAdapters)
    {
        $this->vcsAdapters = $vcsAdapters instanceof Traversable
            ? iterator_to_array($vcsAdapters)
            : $vcsAdapters;
    }

    /**
     * @throws \VcsConnector\Exception\AdapterDoesNotExist
     *
     * @return \VcsConnector\Vcs\Adapter\VcsInterface
     */
    public function resolve(): VcsInterface
    {
        $name = '';
        foreach ($this->vcsAdapters as $adapter) {
            return $adapter;
        }

        throw new AdapterDoesNotExist(sprintf('`%s` csv adapter doesn\'t not exist.', $name));
    }
}
