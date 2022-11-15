<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Adapter\Github;

use VcsConnector\Adapter\VcsInterface;
use VcsConnector\Executor\VcsProcessExecutor;

class GithubVcsAdapter implements VcsInterface
{
    /**
     * @var string
     */
    public const GITHUB = 'github';

    /**
     * @var \VcsConnector\Executor\VcsProcessExecutor
     */
    protected VcsProcessExecutor $vcsProcessExecutor;

    /**
     * @param \VcsConnector\Executor\VcsProcessExecutor $vcsProcessExecutor
     */
    public function __construct(VcsProcessExecutor $vcsProcessExecutor)
    {
        $this->vcsProcessExecutor = $vcsProcessExecutor;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::GITHUB;
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
