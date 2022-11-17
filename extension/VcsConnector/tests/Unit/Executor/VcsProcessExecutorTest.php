<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Tests\Unit\Executor;

use Codeception\Test\Unit;
use VcsConnector\Exception\VcsCommandException;
use VcsConnector\Executor\VcsProcessExecutor;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Executor
 * @group VcsProcessExecutorTest
 * Add your own group annotations below this line
 */
class VcsProcessExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testProcess(): void
    {
        // Arrange
        $vcsProcessExecutor = new VcsProcessExecutor();

        // Act
        $vcsProcessExecutor->process('./', ['pwd']);
    }

    /**
     * @return void
     */
    public function testProcessError(): void
    {
        // Arrange
        $vcsProcessExecutor = new VcsProcessExecutor();

        // Assert
        $this->expectException(VcsCommandException::class);

        // Act
        $vcsProcessExecutor->process('./', ['test']);
    }
}
