<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * @group Acceptance
 * @group Extension
 * @group TaskYaml
 * @group AnalyzerReportTaskCest
 */
class AnalyzerReportTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'analyze:php:code-compliance-report';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'upgrader_success_project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerReportRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }
}
