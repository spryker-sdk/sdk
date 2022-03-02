<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class AnalyzerReportTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'analyze:php:code-compliance-report';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerReportRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports('upgrader_success_project');

        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND],
            $I->getPathFromTestsDataRoot('upgrader_success_project'),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }
}
