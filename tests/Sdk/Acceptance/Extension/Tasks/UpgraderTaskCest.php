<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class UpgraderTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'upgradability:php:upgrade';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testUpgraderFailingBecauseOfEnvs(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports('upgrader_failing_project');

        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND],
            $I->getPathFromTestsDataRoot('upgrader_failing_project'),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertEmpty($process->getOutput());
        Assert::assertStringContainsString(
            sprintf('Error thrown while running command "%s"', static::COMMAND),
            $process->getErrorOutput(),
        );
    }
}
