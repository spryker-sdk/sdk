<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\VcsConnector\Adapter\Github;

use Codeception\Test\Unit;
use VcsConnector\Vcs\Adapter\Github\GithubConnector;
use VcsConnector\Vcs\VcsProcessExecutor;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group VcsConnector
 * @group Adapter
 * @group Github
 * @group GithubConnectorTest
 * Add your own group annotations below this line
 */
class GithubConnectorTest extends Unit
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

        $githubVcsAdapter = new GithubConnector($vcsProcessExecutorMock);

        // Act
        $githubVcsAdapter->clone('./', 'test');
    }
}
