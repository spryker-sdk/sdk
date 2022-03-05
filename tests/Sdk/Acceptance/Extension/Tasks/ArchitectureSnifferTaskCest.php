<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class ArchitectureSnifferTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:architecture';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testArchitectureSnifferRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports('architecture_project');

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--strict=no',
                '--priority=2',
            ],
            $I->getPathFromTestsDataRoot('architecture_project'),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testArchitectureSnifferFindingViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports('architecture_project');

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--strict=no',
                '--priority=3',
            ],
            $I->getPathFromTestsDataRoot('architecture_project'),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
    }
}
