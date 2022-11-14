<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs\Adapter\Github;

use VcsConnector\Vcs\VcsProcessExecutor;

class GithubConnector
{
    /**
     * @var \VcsConnector\Vcs\VcsProcessExecutor
     */
    protected VcsProcessExecutor $vcsProcessExecutor;

    /**
     * @param \VcsConnector\Vcs\VcsProcessExecutor $vcsProcessExecutor
     */
    public function __construct(VcsProcessExecutor $vcsProcessExecutor)
    {
        $this->vcsProcessExecutor = $vcsProcessExecutor;
    }

    /**
     * @param string $projectPath
     * @param string $branch
     *
     * @return void
     */
    public function clone(string $projectPath, string $branch): void
    {
        $command = ['git', 'clone', $branch];

        $this->vcsProcessExecutor->process($projectPath, $command);
    }
}