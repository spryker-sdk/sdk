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
     * @var string
     */
    protected const SUCCESS_PROJECT_DIR = 'upgrader_success_project';

    /**
     * @var string
     */
    protected const FAIL_PROJECT_DIR = 'upgrader_failing_project';

    /**
     * @skip
     *
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::SUCCESS_PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND],
            $I->getProjectRoot(static::SUCCESS_PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @skip
     *
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerFindingViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::FAIL_PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND],
            $I->getProjectRoot(static::FAIL_PROJECT_DIR),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertNotEmpty($process->getOutput());
        Assert::assertStringContainsString('Violations found on project level', $process->getOutput());
    }

    /**
     * @skip
     *
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testAnalyzerGeneratingFileReport(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::FAIL_PROJECT_DIR);

        // Act
        $I->runSdkCommand(
            [
                static::COMMAND,
                '--format=yaml',
            ],
            $I->getProjectRoot(static::FAIL_PROJECT_DIR),
        );

        // Assert
        Assert::assertFileExists(
            $I->getPathFromProjectRoot('reports/' . static::COMMAND . '.violations.yaml', static::FAIL_PROJECT_DIR),
        );
    }
}
