<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Tests\Unit\Adapter\Github;

use Codeception\Test\Unit;
use VcsConnector\Adapter\Github\GithubVcsAdapter;
use VcsConnector\Executor\VcsProcessExecutor;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Adapter
 * @group Github
 * @group GithubVcsAdapterTest
 * Add your own group annotations below this line
 */
class GithubVcsAdapterTest extends Unit
{
    /**
     * @return void
     */
    public function testClone(): void
    {
        // Arrange
        $vcsProcessExecutorMock = $this->createMock(VcsProcessExecutor::class);
        $vcsProcessExecutorMock->expects($this->once())
            ->method('process')
            ->with('./', ['git', 'clone', 'test']);

        $githubVcsAdapter = new GithubVcsAdapter($vcsProcessExecutorMock);

        // Act
        $githubVcsAdapter->clone('./', 'test');
    }
}
