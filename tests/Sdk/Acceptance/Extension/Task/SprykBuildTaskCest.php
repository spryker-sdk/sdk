<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Acceptance
 * @group Extension
 * @group Task
 * @group SprykBuildTaskCest
 * Add your own group annotations below this line
 */
class SprykBuildTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'spryk:build';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykBuildRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanSprykGenerated();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('generated/spryk_argument_list.yml'));
    }
}
