<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs\Adapter;

class GithubVcsAdapter implements VcsInterface
{
    /**
     * @var string
     */
    public const GITHUB = 'github';

    /**
     * @var \VcsConnector\Vcs\Adapter\GithubConnector
     */
    public GithubConnector $githubConnector;

    /**
     * @param \VcsConnector\Vcs\Adapter\GithubConnector $githubConnector
     */
    public function __construct(GithubConnector $githubConnector)
    {
        $this->githubConnector = $githubConnector;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::GITHUB;
    }
}
