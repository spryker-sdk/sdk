<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs\Adapter;

use Github\AuthMethod;
use Github\Client;
use VcsConnector\Vcs\VcsProcessExecutor;

class GithubConnector
{
    /**
     * @var \Github\Client
     */
    protected Client $githubClient;

    /**
     * @var \VcsConnector\Vcs\VcsProcessExecutor
     */
    protected VcsProcessExecutor $vcsProcessExecutor;

    /**
     * @param \Github\Client $githubClient
     * @param \VcsConnector\Vcs\VcsProcessExecutor $vcsProcessExecutor
     */
    public function __construct(Client $githubClient, VcsProcessExecutor $vcsProcessExecutor)
    {
        $this->githubClient = $githubClient;
        $this->vcsProcessExecutor = $vcsProcessExecutor;
    }

    /**
     * @param string $token
     *
     * @return void
     */
    protected function authenticated(string $token): void
    {
        $this->githubClient->authenticate($token, null, AuthMethod::ACCESS_TOKEN);
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
