<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs;

use VcsConnector\Vcs\Adapter\VcsInterface;

interface VcsConfigurationResolverInterface
{
    /**
     * @throws \VcsConnector\Exception\AdapterDoesNotExist
     *
     * @return \VcsConnector\Vcs\Adapter\VcsInterface
     */
    public function resolve(): VcsInterface;
}
