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
     * @param iterable $vcsAdapters
     */
    public function __construct(iterable $vcsAdapters)
    {
        $this->vcsAdapters = $vcsAdapters instanceof Traversable
            ? iterator_to_array($vcsAdapters)
            : $vcsAdapters;
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
