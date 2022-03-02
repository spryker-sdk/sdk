<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class AnalyzerTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'analyze:php:code-compliance';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerRunsSuccessfully(AcceptanceTester $I): void
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

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerFindingViolations(AcceptanceTester $I): void
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
        Assert::assertNotEmpty($process->getOutput());
        Assert::assertStringContainsString('Violations found on project level', $process->getOutput());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerGeneratingFileReport(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports('upgrader_failing_project');

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--format=yaml',
            ],
            $I->getPathFromTestsDataRoot('upgrader_failing_project'),
        );

        // Assert
        Assert::assertFileExists(
            $I->getPathFromTestsDataRoot('upgrader_failing_project/reports/' . static::COMMAND . '.violations.yaml'),
        );
    }
}
