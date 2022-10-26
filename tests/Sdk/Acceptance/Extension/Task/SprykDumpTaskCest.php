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
 * @group SprykDumpTaskCest
 * Add your own group annotations below this line
 */
class SprykDumpTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'spryk:dump';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykDumpRunsSuccessfully(AcceptanceTester $I): void
    {
        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykDumpWithSpecifiedSprykRunsSuccessfully(AcceptanceTester $I): void
    {
        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--spryk=AddModuleGui',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykDumpWithSpecifiedLevelRunsSuccessfully(AcceptanceTester $I): void
    {
        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--level=2',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykDumpWithSpecifiedSprykAndLevelRunsSuccessfully(AcceptanceTester $I): void
    {
        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--spryk=AddModuleGui',
            '--level=2',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }
}
