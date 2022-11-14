<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\VcsConnector\Adapter\Github;

use Codeception\Test\Unit;
use VcsConnector\Vcs\Adapter\Github\GithubConnector;
use VcsConnector\Vcs\Adapter\Github\GithubVcsAdapter;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group VcsConnector
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
        $githubConnectorMock = $this->createMock(GithubConnector::class);
        $githubConnectorMock->expects($this->once())
            ->method('clone')
            ->with('./', 'test');
        $githubVcsAdapter = new GithubVcsAdapter($githubConnectorMock);

        // Act
        $githubVcsAdapter->clone('./', 'test');
    }
}
