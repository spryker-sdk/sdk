<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs\Adapter;

use Gitlab\Client;

class GithubConnector
{
    /**
     * @var \Gitlab\Client
     */
    protected Client $githubClient;

    /**
     * @param \Gitlab\Client $githubClient
     */
    public function __construct(Client $githubClient)
    {
        $this->githubClient = $githubClient;
    }
}
